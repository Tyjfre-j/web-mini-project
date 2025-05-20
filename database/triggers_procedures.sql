-- Procedure to display order details and total for a customer
DELIMITER //
CREATE PROCEDURE GetOrderDetails(IN orderId INT)
BEGIN
    -- Get order header information
    SELECT o.order_id, o.order_date, o.total_amount, o.shipping_address, 
           o.payment_method, o.order_status, c.customer_fname, c.customer_email
    FROM orders o
    JOIN customer c ON o.customer_id = c.customer_id
    WHERE o.order_id = orderId;
    
    -- Get order items details
    SELECT oi.item_id, oi.product_name, oi.quantity, oi.price, oi.subtotal
    FROM order_items oi
    WHERE oi.order_id = orderId;
END //
DELIMITER ;

-- Procedure to finalize an order and empty the cart
DELIMITER //
CREATE PROCEDURE FinalizeOrder(
    IN customerId INT,
    IN shippingAddress TEXT,
    IN billingAddress TEXT,
    IN paymentMethod VARCHAR(50),
    IN orderNotes TEXT,
    OUT newOrderId INT
)
BEGIN
    DECLARE totalAmount DECIMAL(10,2) DEFAULT 0;
    
    -- Start transaction
    START TRANSACTION;
    
    -- Calculate total amount from cart items (assuming there's a temporary cart table or session)
    -- In this implementation, we'll calculate directly from passed cart items
    
    -- Insert order header
    INSERT INTO orders (
        customer_id, order_date, total_amount, 
        shipping_address, billing_address, 
        payment_method, order_notes, order_status
    ) VALUES (
        customerId, NOW(), 0, -- Total will be updated later
        shippingAddress, billingAddress,
        paymentMethod, orderNotes, 'pending'
    );
    
    -- Get the new order ID
    SET newOrderId = LAST_INSERT_ID();
    
    -- The cart items will be inserted by the application code
    -- and the total will be calculated and updated
    
    COMMIT;
END //
DELIMITER ;

-- Procedure to get order history for a customer
DELIMITER //
CREATE PROCEDURE GetCustomerOrderHistory(IN customerId INT)
BEGIN
    SELECT o.order_id, o.order_date, o.total_amount, o.order_status,
           COUNT(oi.item_id) AS item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.customer_id = customerId
    GROUP BY o.order_id
    ORDER BY o.order_date DESC;
END //
DELIMITER ;

-- Create a table to track canceled orders
CREATE TABLE IF NOT EXISTS canceled_orders_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    customer_id INT NOT NULL,
    cancel_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    reason VARCHAR(255),
    canceled_by VARCHAR(50) DEFAULT 'customer',
    FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
);

-- Trigger to update product stock after order validation
DELIMITER //
CREATE TRIGGER after_order_items_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE product_table VARCHAR(50);
    DECLARE product_id_field VARCHAR(50);
    DECLARE quantity_field VARCHAR(50);
    
    -- Determine which product table to update based on product_type
    SET product_table = NEW.product_type;
    SET product_id_field = CONCAT(product_table, '_id');
    SET quantity_field = CONCAT(product_table, '_quantity');
    
    -- Use dynamic SQL to update the appropriate product table
    SET @sql = CONCAT('UPDATE `', product_table, '` SET `', quantity_field, '` = `', 
                      quantity_field, '` - ', NEW.quantity, 
                      ' WHERE `', product_id_field, '` = ', NEW.product_id);
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //
DELIMITER ;

-- Trigger to check stock before order item insertion
DELIMITER //
CREATE TRIGGER before_order_items_insert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    DECLARE product_table VARCHAR(50);
    DECLARE product_id_field VARCHAR(50);
    DECLARE quantity_field VARCHAR(50);
    
    -- Determine which product table to check based on product_type
    SET product_table = NEW.product_type;
    SET product_id_field = CONCAT(product_table, '_id');
    SET quantity_field = CONCAT(product_table, '_quantity');
    
    -- Use dynamic SQL to get the available stock
    SET @sql = CONCAT('SELECT `', quantity_field, '` INTO @stock FROM `', 
                      product_table, '` WHERE `', product_id_field, '` = ', NEW.product_id);
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET available_stock = @stock;
    
    -- Check if requested quantity exceeds available stock
    IF NEW.quantity > available_stock THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Insufficient stock available';
    END IF;
END //
DELIMITER ;

-- Trigger to restore stock when order is canceled
DELIMITER //
CREATE TRIGGER after_order_status_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE item_id INT;
    DECLARE product_id INT;
    DECLARE product_type VARCHAR(50);
    DECLARE item_quantity INT;
    
    -- Cursor for order items
    DECLARE cur CURSOR FOR 
        SELECT item_id, product_id, product_type, quantity 
        FROM order_items 
        WHERE order_id = NEW.order_id;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Only process when order status changes to 'cancelled'
    IF OLD.order_status != 'cancelled' AND NEW.order_status = 'cancelled' THEN
        -- Log to canceled orders history
        INSERT INTO canceled_orders_history (
            order_id, customer_id, total_amount
        ) VALUES (
            NEW.order_id, NEW.customer_id, NEW.total_amount
        );
        
        -- For each order item, restore stock
        OPEN cur;
        
        read_loop: LOOP
            FETCH cur INTO item_id, product_id, product_type, item_quantity;
            
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            -- Use dynamic SQL to restore stock in the appropriate product table
            SET @product_table = product_type;
            SET @product_id_field = CONCAT(@product_table, '_id');
            SET @quantity_field = CONCAT(@product_table, '_quantity');
            
            SET @sql = CONCAT('UPDATE `', @product_table, '` SET `', @quantity_field, '` = `', 
                             @quantity_field, '` + ', item_quantity, 
                             ' WHERE `', @product_id_field, '` = ', product_id);
            
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END LOOP;
        
        CLOSE cur;
    END IF;
END //
DELIMITER ; 