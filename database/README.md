# PeakGear Database Procedures and Triggers

This directory contains the SQL files for the database schema, procedures, and triggers used in the PeakGear e-commerce website.

## Overview

The database implementation includes:

- Stored procedures for common operations
- Database triggers for automatic stock management
- History tracking for canceled orders

## Stored Procedures

### 1. GetOrderDetails

**Purpose**: Fetches complete order details including customer information and all order items.

**Usage**:
```sql
CALL GetOrderDetails(order_id);
```

**Returns**: Two result sets:
- Order header with customer information
- All order items associated with the order

### 2. FinalizeOrder

**Purpose**: Handles order creation with all necessary data.

**Usage**:
```sql
CALL FinalizeOrder(customer_id, shipping_address, billing_address, payment_method, order_notes, @new_order_id);
SELECT @new_order_id; -- To get the new order ID
```

**Returns**: The new order ID via an OUT parameter.

### 3. GetCustomerOrderHistory

**Purpose**: Retrieves a customer's complete order history.

**Usage**:
```sql
CALL GetCustomerOrderHistory(customer_id);
```

**Returns**: A result set with all orders for the specified customer.

## Triggers

### 1. before_order_items_insert

**Purpose**: Prevents order item insertion if requested quantity exceeds available stock.

**Activation**: BEFORE INSERT on order_items table.

**Action**: Validates that requested quantity is available in stock before allowing the insert operation.

### 2. after_order_items_insert

**Purpose**: Automatically updates product stock after order item insertion.

**Activation**: AFTER INSERT on order_items table.

**Action**: Reduces product stock by the ordered quantity.

### 3. after_order_status_update

**Purpose**: Restores product stock when an order is canceled and logs to the canceled_orders_history table.

**Activation**: AFTER UPDATE on orders table.

**Action**: If order status is changed to 'cancelled', restores stock quantities and logs the cancellation.

## PHP Implementation

The PHP implementation is located in `includes/db_procedures.php` and provides functions that call these stored procedures:

- `getOrderDetails($orderId)`
- `finalizeOrder($customerId, $shippingAddress, $billingAddress, $paymentMethod, $orderNotes)`
- `getCustomerOrderHistory($customerId)`
- `addOrderItem($orderId, $productId, $productType, $productName, $quantity, $price)`
- `cancelOrder($orderId, $reason)`

## Installation

To install/update the database procedures and triggers, run the `install_triggers.php` script from your web browser or command line:

```
http://yoursite.com/install_triggers.php
```

This script will execute the SQL statements defined in `database/triggers_procedures.sql`. 