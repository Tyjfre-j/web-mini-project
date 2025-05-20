<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
include_once('./includes/headerNav.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['cart_error'] = "You must be an administrator to access this page";
    header('location:login.php');
    exit();
}

// Initialize variables
$error_message = '';
$success_message = '';
$report_type = isset($_GET['report']) ? $_GET['report'] : 'sales';
$time_period = isset($_GET['period']) ? $_GET['period'] : 'monthly';
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$product_type = isset($_GET['product_type']) ? $_GET['product_type'] : '';

// Get all available years for filtering
$years = [];
$stmt = $conn->prepare("SELECT DISTINCT year FROM detailed_order_history ORDER BY year DESC");
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
    }
}
$stmt->close();

// Get all product types for filtering
$product_types = [];
$stmt = $conn->prepare("SELECT DISTINCT product_type FROM detailed_order_history ORDER BY product_type");
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $product_types[] = $row['product_type'];
    }
}
$stmt->close();

// Generate report data based on type and filters
$report_data = [];
$report_title = '';

switch($report_type) {
    case 'sales':
        $report_title = 'Sales Report';
        
        if($time_period == 'monthly') {
            // Monthly sales report
            $query = "SELECT 
                        product_type, 
                        SUM(quantity) as total_qty, 
                        SUM(subtotal) as total_sales,
                        COUNT(DISTINCT order_id) as order_count
                      FROM detailed_order_history 
                      WHERE year = ? AND month = ? 
                        AND order_status = 'delivered'";
            
            if(!empty($product_type)) {
                $query .= " AND product_type = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iis", $year, $month, $product_type);
            } else {
                $query .= " GROUP BY product_type ORDER BY total_sales DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $year, $month);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $report_data[] = $row;
                }
            }
            $stmt->close();
            
        } else if($time_period == 'yearly') {
            // Yearly sales report
            $query = "SELECT 
                        month, 
                        SUM(quantity) as total_qty, 
                        SUM(subtotal) as total_sales,
                        COUNT(DISTINCT order_id) as order_count
                      FROM detailed_order_history 
                      WHERE year = ? 
                        AND order_status = 'delivered'";
            
            if(!empty($product_type)) {
                $query .= " AND product_type = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("is", $year, $product_type);
            } else {
                $query .= " GROUP BY month ORDER BY month";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $year);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $report_data[] = $row;
                }
            }
            $stmt->close();
        }
        break;
        
    case 'products':
        $report_title = 'Product Performance Report';
        
        $query = "SELECT 
                    product_type,
                    product_id,
                    product_name, 
                    SUM(quantity) as total_qty, 
                    AVG(unit_price) as avg_price,
                    SUM(subtotal) as total_sales,
                    COUNT(DISTINCT order_id) as order_count
                  FROM detailed_order_history 
                  WHERE order_status = 'delivered'";
        
        if($time_period == 'monthly') {
            $query .= " AND year = ? AND month = ?";
        } else {
            $query .= " AND year = ?";
        }
        
        if(!empty($product_type)) {
            $query .= " AND product_type = ?";
        }
        
        $query .= " GROUP BY product_type, product_id, product_name ORDER BY total_sales DESC LIMIT 20";
        
        if($time_period == 'monthly') {
            if(!empty($product_type)) {
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iis", $year, $month, $product_type);
            } else {
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $year, $month);
            }
        } else {
            if(!empty($product_type)) {
                $stmt = $conn->prepare($query);
                $stmt->bind_param("is", $year, $product_type);
            } else {
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $year);
            }
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $report_data[] = $row;
            }
        }
        $stmt->close();
        break;
        
    case 'customers':
        $report_title = 'Customer Purchase Report';
        
        $query = "SELECT 
                    doh.customer_id,
                    c.customer_fname,
                    c.customer_email,
                    COUNT(DISTINCT doh.order_id) as order_count,
                    SUM(doh.quantity) as total_items,
                    SUM(doh.subtotal) as total_spent,
                    MAX(doh.order_date) as last_order_date
                  FROM detailed_order_history doh
                  JOIN customer c ON doh.customer_id = c.customer_id
                  WHERE doh.order_status = 'delivered'";
        
        if($time_period == 'monthly') {
            $query .= " AND doh.year = ? AND doh.month = ?";
        } else {
            $query .= " AND doh.year = ?";
        }
        
        $query .= " GROUP BY doh.customer_id, c.customer_fname, c.customer_email ORDER BY total_spent DESC LIMIT 20";
        
        if($time_period == 'monthly') {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $year, $month);
        } else {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $year);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $report_data[] = $row;
            }
        }
        $stmt->close();
        break;
}

// Get monthly names for display
$month_names = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
?>

<div class="overlay" data-overlay></div>

<header>
    <?php require_once './includes/topheadactions.php'; ?>
    <?php require_once './includes/desktopnav.php' ?>
    <?php require_once './includes/mobilenav.php'; ?>
</header>

<style>
:root {
    --primary-color: #0d8a91;
    --primary-dark: #00656b;
    --secondary-color: #69585f;
    --accent-color: #00656b;
    --success-color: #38b000;
    --danger-color: #e63946;
    --warning-color: #ffb703;
    --text-dark: #333333;
    --text-light: #f8f9fa;
    --bg-light: #f8f9fa;
    --bg-primary: #e9f0f2;
    --bg-secondary: #f0f5f6;
    --bg-accent: #e3f2f3;
    --gray-light: #f8f9fa;
    --gray-medium: #e9ecef;
    --gray-dark: #6c757d;
    --border-light: #e0e0e0;
    --border-radius-md: 10px;
    --border-radius-sm: 5px;
    --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.admin-container {
    padding: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 1.8rem;
    color: var(--text-dark);
    font-weight: 700;
    margin: 0;
    position: relative;
}

.page-title::after {
    content: '';
    position: absolute;
    height: 3px;
    width: 50px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
    left: 0;
    bottom: -8px;
    border-radius: 2px;
}

.report-container {
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
    overflow: hidden;
}

.report-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 1.5rem;
}

.report-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.filter-section {
    padding: 1rem 1.5rem;
    background: var(--bg-light);
    border-bottom: 1px solid var(--border-light);
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: flex-end;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--gray-dark);
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.5rem 0.75rem;
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--text-dark);
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-sm);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: var(--text-dark);
    background-color: #fff;
    border-color: var(--primary-color);
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(13, 138, 145, 0.25);
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
}

.btn ion-icon {
    margin-right: 0.25rem;
    font-size: 1.1rem;
}

.report-content {
    padding: 1.5rem;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
}

.data-table th {
    background: var(--bg-light);
    font-weight: 600;
    color: var(--gray-dark);
    font-size: 0.9rem;
    position: sticky;
    top: 0;
}

.data-table tbody tr:hover {
    background-color: var(--bg-light);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.money {
    font-family: monospace;
    text-align: right;
}

.number {
    font-family: monospace;
    text-align: right;
}

.chart-container {
    padding: 1rem;
    height: 400px;
}

.report-footer {
    padding: 1rem 1.5rem;
    background: var(--bg-light);
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-totals {
    display: flex;
    gap: 1.5rem;
}

.total-item {
    display: flex;
    flex-direction: column;
}

.total-label {
    font-size: 0.9rem;
    color: var(--gray-dark);
}

.total-value {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.2rem;
}

.report-actions {
    display: flex;
    gap: 0.75rem;
}

.nav-tabs {
    display: flex;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: var(--bg-light);
    border-bottom: 1px solid var(--border-light);
}

.nav-tab {
    padding: 0.75rem 1.5rem;
    background: var(--bg-light);
    color: var(--gray-dark);
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: 500;
}

.nav-tab:hover {
    background: var(--bg-primary);
    color: var(--primary-dark);
    text-decoration: none;
}

.nav-tab.active {
    background: var(--primary-color);
    color: white;
    text-decoration: none;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state-icon {
    font-size: 3rem;
    color: var(--gray-dark);
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.empty-state-text {
    color: var(--gray-dark);
    max-width: 500px;
    margin: 0 auto 1.5rem;
}

@media (max-width: 768px) {
    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .summary-totals {
        flex-direction: column;
        gap: 1rem;
    }
    
    .report-footer {
        flex-direction: column;
        gap: 1rem;
    }
    
    .report-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<main>
    <div class="admin-container">
        <div class="page-header">
            <h1 class="page-title">Admin Reports</h1>
            <a href="admin_dashboard.php" class="btn btn-outline">
                <ion-icon name="arrow-back-outline"></ion-icon> Back to Dashboard
            </a>
        </div>
        
        <div class="report-container">
            <div class="report-header">
                <h2 class="report-title"><?php echo $report_title; ?></h2>
            </div>
            
            <div class="nav-tabs">
                <a href="?report=sales" class="nav-tab <?php echo $report_type === 'sales' ? 'active' : ''; ?>">Sales Report</a>
                <a href="?report=products" class="nav-tab <?php echo $report_type === 'products' ? 'active' : ''; ?>">Product Performance</a>
                <a href="?report=customers" class="nav-tab <?php echo $report_type === 'customers' ? 'active' : ''; ?>">Customer Analysis</a>
            </div>
            
            <div class="filter-section">
                <form action="" method="GET" class="filter-form">
                    <input type="hidden" name="report" value="<?php echo $report_type; ?>">
                    
                    <div class="form-group">
                        <label for="period">Time Period</label>
                        <select name="period" id="period" class="form-control" onchange="this.form.submit()">
                            <option value="monthly" <?php echo $time_period === 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                            <option value="yearly" <?php echo $time_period === 'yearly' ? 'selected' : ''; ?>>Yearly</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                            <?php foreach($years as $y): ?>
                                <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <?php if($time_period === 'monthly'): ?>
                    <div class="form-group">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-control" onchange="this.form.submit()">
                            <?php foreach($month_names as $num => $name): ?>
                                <option value="<?php echo $num; ?>" <?php echo $month == $num ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($report_type === 'sales' || $report_type === 'products'): ?>
                    <div class="form-group">
                        <label for="product_type">Product Type</label>
                        <select name="product_type" id="product_type" class="form-control" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <?php foreach($product_types as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo $product_type === $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <ion-icon name="filter-outline"></ion-icon> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="report-content">
                <?php if(empty($report_data)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </div>
                        <h2 class="empty-state-title">No Data Available</h2>
                        <p class="empty-state-text">There is no data available for the selected filters. Try changing your selection or check back later.</p>
                    </div>
                <?php else: ?>
                    <?php if($report_type === 'sales'): ?>
                        <?php if($time_period === 'monthly'): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Product Type</th>
                                        <th>Order Count</th>
                                        <th>Items Sold</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_orders = 0;
                                    $total_items = 0;
                                    $total_sales = 0;
                                    
                                    foreach($report_data as $row): 
                                        $total_orders += $row['order_count'];
                                        $total_items += $row['total_qty'];
                                        $total_sales += $row['total_sales'];
                                    ?>
                                        <tr>
                                            <td><?php echo $row['product_type']; ?></td>
                                            <td class="number"><?php echo $row['order_count']; ?></td>
                                            <td class="number"><?php echo $row['total_qty']; ?></td>
                                            <td class="money">$<?php echo number_format($row['total_sales'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Order Count</th>
                                        <th>Items Sold</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_orders = 0;
                                    $total_items = 0;
                                    $total_sales = 0;
                                    
                                    foreach($report_data as $row): 
                                        $total_orders += $row['order_count'];
                                        $total_items += $row['total_qty'];
                                        $total_sales += $row['total_sales'];
                                    ?>
                                        <tr>
                                            <td><?php echo $month_names[$row['month']]; ?></td>
                                            <td class="number"><?php echo $row['order_count']; ?></td>
                                            <td class="number"><?php echo $row['total_qty']; ?></td>
                                            <td class="money">$<?php echo number_format($row['total_sales'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php elseif($report_type === 'products'): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product Type</th>
                                    <th>Product Name</th>
                                    <th>Avg Price</th>
                                    <th>Quantity Sold</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_items = 0;
                                $total_sales = 0;
                                
                                foreach($report_data as $row): 
                                    $total_items += $row['total_qty'];
                                    $total_sales += $row['total_sales'];
                                ?>
                                    <tr>
                                        <td><?php echo $row['product_type']; ?></td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td class="money">$<?php echo number_format($row['avg_price'], 2); ?></td>
                                        <td class="number"><?php echo $row['total_qty']; ?></td>
                                        <td class="money">$<?php echo number_format($row['total_sales'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif($report_type === 'customers'): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Items</th>
                                    <th>Total Spent</th>
                                    <th>Last Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_orders = 0;
                                $total_items = 0;
                                $total_sales = 0;
                                
                                foreach($report_data as $row): 
                                    $total_orders += $row['order_count'];
                                    $total_items += $row['total_items'];
                                    $total_sales += $row['total_spent'];
                                ?>
                                    <tr>
                                        <td><?php echo $row['customer_fname']; ?></td>
                                        <td><?php echo $row['customer_email']; ?></td>
                                        <td class="number"><?php echo $row['order_count']; ?></td>
                                        <td class="number"><?php echo $row['total_items']; ?></td>
                                        <td class="money">$<?php echo number_format($row['total_spent'], 2); ?></td>
                                        <td><?php echo date("M j, Y", strtotime($row['last_order_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <?php if(!empty($report_data)): ?>
            <div class="report-footer">
                <div class="summary-totals">
                    <?php if(isset($total_orders)): ?>
                    <div class="total-item">
                        <div class="total-label">Total Orders</div>
                        <div class="total-value"><?php echo $total_orders; ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($total_items)): ?>
                    <div class="total-item">
                        <div class="total-label">Total Items</div>
                        <div class="total-value"><?php echo $total_items; ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($total_sales)): ?>
                    <div class="total-item">
                        <div class="total-label">Total Sales</div>
                        <div class="total-value">$<?php echo number_format($total_sales, 2); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="report-actions">
                    <button onclick="printReport()" class="btn btn-primary">
                        <ion-icon name="print-outline"></ion-icon> Print Report
                    </button>
                    <button onclick="exportToCSV()" class="btn btn-primary">
                        <ion-icon name="download-outline"></ion-icon> Export CSV
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
function printReport() {
    window.print();
}

function exportToCSV() {
    // Get table data
    const table = document.querySelector('.data-table');
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Replace $ and commas in money fields
            let text = cols[j].innerText.replace(/\$/g, '').replace(/,/g, '');
            // Add quotes if there's a comma in the text (after we've removed formatting commas)
            if (text.includes(',')) {
                text = `"${text}"`;
            }
            row.push(text);
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV file
    const csvString = csv.join('\n');
    const filename = '<?php echo $report_title; ?>_<?php echo date("Y-m-d"); ?>.csv';
    
    const link = document.createElement('a');
    link.style.display = 'none';
    link.setAttribute('target', '_blank');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvString));
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<?php require_once './includes/footer.php'; ?> 