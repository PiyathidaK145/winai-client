<?php include 'include/header.php';
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daily_sales_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Define menu items and categories dynamically
$categories = [
    "all" => ["น้ำดำ", "ต้มยำ", "น้ำใส", "หม่าล่า", "เนื้อออสเตเรีย", "เนื้อวากิว", "ผักกาดขาว", "ข้าวโพดอ่อน", "กุ้ง", "หมึก", "มาม่า", "เต้าหู้ปลา", "แอปเปิล", "แตงโม", "น้ำอัดลม", "ไอติม"],
    "น้ำซุป" => ["น้ำดำ", "ต้มยำ", "น้ำใส", "หม่าล่า"],
    "เนื้อ" => ["เนื้อออสเตเรีย", "เนื้อวากิว"],
    "ผัก" => ["ผักกาดขาว", "ข้าวโพดอ่อน"],
    "ทะเล" => ["กุ้ง", "หมึก"],
    "อาหารแปรรูป" => ["มาม่า", "เต้าหู้ปลา"],
    "ผลไม้" => ["แอปเปิล", "แตงโม"],
    "อื่นๆ" => ["น้ำอัดลม", "ไอติม"],
];

// Sample data for demonstration
$sampleData = [
    ['C001', 'John Doe', 'Yes', '45 min', [1, 0, 0, 0, 2, 0, 1, 1, 0, 0, 2, 0, 1, 1, 0, 0], '80 บาท', 'Cash', '2024-12-23 12:00:00', 'Table 1'],
    ['C002', 'Jane Smith', 'No', '30 min', [0, 1, 0, 0, 0, 2, 1, 0, 1, 1, 0, 2, 1, 0, 1, 1], '100 บาท', 'Credit Card', '2024-12-24 12:30:00', 'Table 2'],
    ['C003', 'James Bond', 'Yes', '60 min', [0, 0, 0, 1, 1, 1, 1, 2, 1, 0, 1, 1, 1, 2, 1, 0], '80 บาท', 'Mobile Payment', '2024-12-24 1:00:00', 'Table 3'],
];

?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="title-group mb-3"></div>
                <h1 class="h2 mb-0">รายงานยอดขายรายวัน</h1>

                <!-- Date Selection -->
                <div class="card mb-4 p-3">
                    <label for="dateSelect" class="form-label">เลือกวันที่:</label>
                    <input type="date" id="dateSelect" class="form-control" onchange="filterData()">
                </div>

                <div class="card mb-4 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label for="totalPriceDisplay" class="form-label fw-bold">ราคารวม:</label>
                            <label id="totalPriceDisplay" class="form-label">0.00บาท</label>
                        </div>
                        <div>
                            <label for="totalCustomersDisplay" class="form-label fw-bold">จำนวนลูกค้าทั้งหมด:</label>
                            <label id="totalCustomersDisplay" class="form-label">0</label>
                        </div>
                    </div>
                </div>

                <!-- Table Selection Dropdown -->
                <div class="card mb-4 p-3">
                    <label for="tableSelect" class="form-label">เลือกโต๊ะ:</label>
                    <select id="tableSelect" class="form-select" onchange="filterData()">
                        <option value="all">All Tables</option>
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                            <option value="table-<?php echo $i; ?>">Table <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Category Buttons -->
                <div class="mb-3">
                    <?php foreach ($categories as $category => $items): ?>
                        <button type="button" class="btn btn-secondary category-button" data-category="<?php echo $category; ?>" onclick="toggleCategory('<?php echo $category; ?>')">
                            <?php echo ucfirst($category); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Table Display -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr id="tableHeader">
                                <th>#</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Member</th>
                                <th>Time record</th>
                                <?php foreach ($categories["all"] as $menu): ?>
                                    <th data-category="<?php echo $menu; ?>"><?php echo $menu; ?></th>
                                <?php endforeach; ?>
                                <th>Price</th>
                                <th>Channel</th>
                                <th>Time Stamp</th>
                                <th>Table</th>
                            </tr>
                        </thead>
                        <tbody id="salesTableBody">
                            <?php foreach ($sampleData as $row): ?>
                                <tr class="table-<?php echo strtolower(str_replace(' ', '-', str_replace('Table ', '', $row[8]))); ?>">
                                    <td class="row-index"></td>
                                    <td><?php echo $row[0]; ?></td>
                                    <td><?php echo $row[1]; ?></td>
                                    <td><?php echo $row[2]; ?></td>
                                    <td><?php echo $row[3]; ?></td> <!-- Time record -->
                                    <?php foreach ($row[4] as $quantity): ?>
                                        <td><?php echo $quantity; ?></td>
                                    <?php endforeach; ?>
                                    <td><?php echo $row[5]; ?></td> <!-- Price -->
                                    <td><?php echo $row[6]; ?></td> <!-- Channel -->
                                    <td><?php echo $row[7]; ?></td> <!-- Time Stamp -->
                                    <td><?php echo $row[8]; ?></td> <!-- Table -->
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="noDataMessage" class="no-data" style="display: none;">No data available for this table.</p>
                </div>
            </div>

            <?php include 'include/footer.php'; ?>

            <script>
                const categories = <?php echo json_encode($categories); ?>;
                let activeCategories = ["all"];

                function toggleCategory(selectedCategory) {
                    // Toggle the selected category
                    if (selectedCategory === "all") {
                        activeCategories = ["all"];
                    } else {
                        if (activeCategories.includes(selectedCategory)) {
                            activeCategories = activeCategories.filter(cat => cat !== selectedCategory); // Remove if already selected
                        } else {
                            if (activeCategories.includes("all")) {
                                activeCategories = [selectedCategory]; // Replace "all" with specific categories
                            } else {
                                activeCategories.push(selectedCategory); // Add new category
                            }
                        }
                    }

                    updateCategoryButtons();
                    filterData();
                }

                function updateCategoryButtons() {
                    // Update button styles based on activeCategories
                    const buttons = document.querySelectorAll('.category-button');
                    buttons.forEach(button => {
                        if (activeCategories.includes(button.dataset.category)) {
                            button.classList.add('btn-dark');
                            button.classList.remove('btn-secondary');
                        } else {
                            button.classList.remove('btn-dark');
                            button.classList.add('btn-secondary');
                        }
                    });
                }


                function filterData(selectedCategory = null) {
                    const selectedTable = document.getElementById('tableSelect').value; //เลือก Table
                    const selectedDate = document.getElementById('dateSelect').value; //เลือกวันที่
                    const rows = document.querySelectorAll('#salesTableBody tr');
                    const allColumns = document.querySelectorAll('th[data-category]');
                    const staticColumnsCount = 5; // Number of static columns (e.g., #, ID, Name, Member, Time record)

                    let selectedItems = [];
                    if (activeCategories.includes("all")) {
                        selectedItems = categories["all"];
                    } else {
                        activeCategories.forEach(category => {
                            selectedItems = [...new Set([...selectedItems, ...categories[category]])]; // Combine items without duplicates
                        });
                    }
                    let visibleRowCount = 0;

                    // กรองแถว (Rows) ตาม Table และ วันที่
                    rows.forEach(row => {
                        const matchesTable = (selectedTable === 'all' || row.classList.contains(selectedTable)); // ตรวจสอบโต๊ะ
                        const timeStampCell = row.children[row.children.length - 2]; // คอลัมน์ "Time Stamp"
                        // ตรวจสอบและแปลงค่า Time Stamp เป็นวันที่เท่านั้น (YYYY-MM-DD)
                        const rowDate = new Date(timeStampCell.textContent).toISOString().split('T')[0];
                        const matchesDate = (selectedDate === '' || rowDate === selectedDate); // เปรียบเทียบวันที่

                        if (matchesTable && matchesDate) {
                            row.style.display = ''; // แสดงแถว
                            visibleRowCount++;
                        } else {
                            row.style.display = 'none'; // ซ่อนแถว
                        }
                    });

                    // Show or hide columns by category (excluding static columns)
                    allColumns.forEach((column, columnIndex) => {
                        const isVisible = selectedItems.includes(column.textContent);
                        column.style.display = isVisible ? '' : 'none';

                        rows.forEach(row => {
                            const cell = row.children[columnIndex + staticColumnsCount]; // Offset by staticColumnsCount to skip static columns
                            if (cell) cell.style.display = isVisible ? '' : 'none';
                        });
                    });

                    // Ensure static columns are always visible
                    const staticColumns = document.querySelectorAll('th:nth-child(-n+' + staticColumnsCount + ')');
                    staticColumns.forEach(column => {
                        column.style.display = ''; // Ensure static columns are visible
                    });

                    rows.forEach(row => {
                        for (let i = 0; i < staticColumnsCount; i++) {
                            const cell = row.children[i];
                            if (cell) cell.style.display = ''; // Ensure static cells are visible
                        }
                    });

                    // Show or hide "No data" message
                    const noDataMessage = document.getElementById('noDataMessage');
                    noDataMessage.style.display = visibleRowCount === 0 ? 'block' : 'none';

                    updateRowNumbers();
                    calculateTotalPrice();
                    updateCustomerCount();
                }


                function updateRowNumbers() {
                    const rows = document.querySelectorAll('#salesTableBody tr');
                    let currentIndex = 1;

                    rows.forEach(row => {
                        if (row.style.display !== 'none') {
                            const indexCell = row.querySelector('.row-index');
                            if (indexCell) indexCell.textContent = currentIndex++;
                        }
                    });
                }

                function calculateTotalPrice() {
                    const rows = document.querySelectorAll('#salesTableBody tr');
                    let totalPrice = 0;

                    rows.forEach(row => {
                        if (row.style.display !== 'none') {
                            const priceCell = row.querySelector('td:nth-last-child(4)');
                            const price = parseFloat(priceCell.textContent.replace('$', '')) || 0;
                            totalPrice += price;
                        }
                    });

                    document.getElementById('totalPriceDisplay').textContent = `$${totalPrice.toFixed(2)}`;
                }

                function updateCustomerCount() {
                    const rows = document.querySelectorAll('#salesTableBody tr');
                    let customerCount = 0;

                    rows.forEach(row => {
                        if (row.style.display !== 'none') {
                            customerCount++;
                        }
                    });

                    // Update the Total Customers in the input box
                    document.getElementById('totalCustomersDisplay').textContent = customerCount;
                }

                document.addEventListener('DOMContentLoaded', () => {
                    updateCategoryButtons();
                    updateRowNumbers();
                    calculateTotalPrice();
                    updateCustomerCount();
                });
            </script>
            </body>
            </html>