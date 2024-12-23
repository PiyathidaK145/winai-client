<?php
include 'include/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="title-group mb-3"></div>
            <div class="container mt-5">
                <h1 class="text-center mb-4">Table Order List</h1>

                <!-- Table Selection Dropdown -->
                <div class="card mb-4 p-3">
                    <label for="tableSelect" class="form-label">Select Table:</label>
                    <select id="tableSelect" class="form-select" onchange="filterTable()">
                        <option value="all">All Categories</option>
                        <?php
                        // Generate table numbers dynamically
                        for ($i = 1; $i <= 20; $i++) {
                            echo "<option value='table-$i'>Table $i</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Table Display -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Order Time</th>
                                <th>Status</th>
                                <th>Table Number</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            <?php
                            // Sample data for demonstration
                            $sampleData = [
                                [1, 'ORD001', 'Fried Rice', 2, '$10', '12:00 PM', 'Preparing', 'Table 1'], // เดี๋ยวค่อยเปลี่ยน
                                [2, 'ORD002', 'Chicken Soup', 1, '$8', '12:05 PM', 'Delivered', 'Table 2'],
                                [3, 'ORD003', 'Pasta', 3, '$15', '12:10 PM', 'Preparing', 'Table 1'],
                                [4, 'ORD004', 'Steak', 1, '$25', '12:15 PM', 'Pending', 'Table 3'],
                                [5, 'ORD005', 'Salad', 2, '$12', '12:20 PM', 'Delivered', 'Table 2'],
                            ];

                            // Populate table with sample data
                            foreach ($sampleData as $row) {
                                echo "<tr class='table-" . strtolower(str_replace(' ', '-', $row[7])) . "'>";
                                echo "<td>{$row[0]}</td>";
                                echo "<td>{$row[1]}</td>";
                                echo "<td>{$row[2]}</td>";
                                echo "<td>{$row[3]}</td>";
                                echo "<td>{$row[4]}</td>";
                                echo "<td>{$row[5]}</td>";
                                echo "<td>{$row[6]}</td>";
                                echo "<td>{$row[7]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <p id="noDataMessage" class="no-data" style="display: none;">No data available for this table.</p>
                </div>
            </div>

            <script>
                function filterTable() {
                    const selectedTable = document.getElementById('tableSelect').value;
                    const rows = document.querySelectorAll('#orderTableBody tr');
                    let visibleRowCount = 0;

                    rows.forEach(row => {
                        if (selectedTable === 'all' || row.classList.contains(selectedTable)) {
                            row.style.display = '';
                            visibleRowCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show or hide "No data" message
                    const noDataMessage = document.getElementById('noDataMessage');
                    if (visibleRowCount === 0) {
                        noDataMessage.style.display = 'block';
                    } else {
                        noDataMessage.style.display = 'none';
                    }
                }
            </script>

            <script src="js/bootstrap.bundle.min.js"></script>
            </body>

            </html>