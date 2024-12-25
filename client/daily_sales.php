<?php include 'include/header.php';
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daily_sales_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Define menu items and categories dynamically
$categories = [
    "เมนูทั้งหมด" => ["น้ำดำ", "ต้มยำ", "น้ำใส", "หม่าล่า", "เนื้อออสเตรเลีย", "เนื้อวากิว", "ผักกาดขาว", "ข้าวโพดอ่อน", "กุ้ง", "หมึก", "มาม่า", "เต้าหู้ปลา", "แอปเปิล", "แตงโม", "น้ำอัดลม", "ไอติม"],
    "น้ำซุป" => ["น้ำดำ", "ต้มยำ", "น้ำใส", "หม่าล่า"],
    "เนื้อ" => ["เนื้อออส", "เนื้อวากิว"],
    "ผัก" => ["ผักกาดขาว", "ข้าวโพดอ่อน"],
    "ทะเล" => ["กุ้ง", "หมึก"],
    "อาหารแปรรูป" => ["มาม่า", "เต้าหู้ปลา"],
    "ผลไม้" => ["แอปเปิล", "แตงโม"],
    "อื่นๆ" => ["น้ำอัดลม", "ไอติม"],
];

// Sample data for demonstration
$sampleData = [
    ['C001', 'John Doe', 'Yes', '45 min', [1, 0, 0, 0, 2, 0, 1, 1, 0, 0, 2, 0, 1, 1, 0, 0], '80', 'Cash', '2024-12-23 12:00:00', 'Table 1'],
    ['C002', 'Jane Smith', 'No', '30 min', [0, 1, 0, 0, 0, 2, 1, 0, 1, 1, 0, 2, 1, 0, 1, 1], '100', 'Credit Card', '2024-12-24 12:30:00', 'Table 2'],
    ['C003', 'James Bond', 'Yes', '60 min', [0, 0, 0, 1, 1, 1, 1, 2, 1, 0, 1, 1, 1, 2, 1, 0], '80', 'Mobile Payment', '2024-12-24 1:00:00', 'Table 3'],
    ['C004', 'Joh Dean', 'Yes', '45 min', [1, 0, 0, 0, 2, 0, 1, 1, 0, 0, 2, 0, 1, 1, 0, 0], '80', 'Cash', '2024-12-23 12:00:00', 'Table 1'],
    ['C005', 'Jimmy Sweet', 'No', '30 min', [0, 1, 0, 0, 0, 2, 1, 0, 1, 1, 0, 2, 1, 0, 1, 1], '100', 'Credit Card', '2024-12-24 12:30:00', 'Table 2'],
    ['C006', 'Juria Rose', 'Yes', '60 min', [0, 0, 0, 1, 1, 1, 1, 2, 1, 0, 1, 1, 1, 2, 1, 0], '80', 'Mobile Payment', '2024-12-24 1:00:00', 'Table 3']
];

?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="title-group mb-3"></div>
            <h1 class="h2 mb-0">รายงานยอดขายรายวัน</h1>

            <div class="mb-3 d-flex justify-content-end">
                <button class="btn btn-dark me-2">ส่งออกไฟล์ Excel</button>
                <button class="btn btn-dark me-2">ส่งออกไฟล์ CSV</button>
                <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
            </div>


            <!-- Filter Modal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">ตัวเลือกการกรอง</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <!-- Date Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="card p-3">
                                        <strong><label for="dateSelect" class="form-label">เลือกวันที่:</label></strong>
                                        <input type="date" id="dateSelect" class="form-control">
                                        <button type="button" class="btn btn-primary mt-2" onclick="addDate()">เพิ่มวันที่</button>
                                        <div id="dateList" class="mt-3"></div>
                                    </div>
                                </div>

                                <!-- Time Range Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="card p-3">
                                        <strong><label for="startTime" class="form-label">เลือกช่วงเวลา:</label></strong>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <input type="time" id="startTime" class="form-control" placeholder="Start Time">
                                            </div>
                                            <div class="col">
                                                <input type="time" id="endTime" class="form-control" placeholder="End Time">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="addTimeRange()">เพิ่มช่วงเวลา</button>
                                        <div id="timeList" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <!-- เพศ -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">เพศ:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="genderselectAll" onchange="toggleAllCheckboxes(this, '.genderCheckbox')">
                                            <label for="genderselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="genderCheckbox form-check-input" id="male" value="ชาย" onchange="updateSelectAll('.genderCheckbox', 'genderselectAll')">
                                            <label for="male" class="form-check-label text-dark">ชาย</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="genderCheckbox form-check-input" id="female" value="หญิง" onchange="updateSelectAll('.genderCheckbox', 'genderselectAll')">
                                            <label for="female" class="form-check-label text-dark">หญิง</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="genderCheckbox form-check-input" id="other" value="อื่นๆ" onchange="updateSelectAll('.genderCheckbox', 'genderselectAll')">
                                            <label for="other" class="form-check-label text-dark">อื่นๆ</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <!-- ช่วงอายุ -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">ช่วงอายุ:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="ageselectAll" onchange="toggleAllCheckboxes(this, '.ageCheckbox')">
                                            <label for="ageselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="<15" value="<15" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="<15" class="form-check-label text-dark">น้อยกว่า 15</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="15-25" value="15-25" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="15-25" class="form-check-label text-dark">15-25</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="26-35" value="26-35" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="26-35" class="form-check-label text-dark">26-35</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="36-45" value="36-45" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="36-45" class="form-check-label text-dark">36-45</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="46-55" value="46-55" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="46-55" class="form-check-label text-dark">46-55</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id=">55" value=">55" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for=">55" class="form-check-label text-dark">55 ขึ้นไป</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <!-- ศาสนา -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">ศาสนา:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="religionselectAll" onchange="toggleAllCheckboxes(this, '.religionCheckbox')">
                                            <label for="religionselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="Buddhist" value="พุทธ" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="Buddhist" class="form-check-label text-dark">พุทธ</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="Christ" value="คริสต์" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="Christ" class="form-check-label text-dark">คริสต์</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="Islam" value="อิสลาม" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="Islam" class="form-check-label text-dark">อิสลาม</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="otherReligion" value="อื่นๆ" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="otherReligion" class="form-check-label text-dark">อื่นๆ</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <!-- สมาชิก -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">สมาชิก:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="memberselectAll" onchange="toggleAllCheckboxes(this, '.memberCheckbox')">
                                            <label for="memberselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="memberCheckbox form-check-input" id="member" value="เป็นสมาชิก" onchange="updateSelectAll('.memberCheckbox', 'memberselectAll')">
                                            <label for="member" class="form-check-label text-dark">เป็นสมาชิก</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="memberCheckbox form-check-input" id="notMember" value="ไม่เป็นสมาชิก" onchange="updateSelectAll('.memberCheckbox', 'memberselectAll')">
                                            <label for="notMember" class="form-check-label text-dark">ไม่เป็นสมาชิก</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- "เมนูทั้งหมด" -->
                                <div class="col-md-12 mb-3">
                                    <div class="card p-3 h-100">
                                        <label class="form-label fw-bold text-dark">เมนูทั้งหมด</label>
                                        <div>
                                            <input type="checkbox" id="selectAllMenus" class="form-check-input" onchange="toggleAllMenus()">
                                            <label for="selectAllMenus" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categories -->
                                <?php foreach ($categories as $category => $items): ?>
                                    <?php if ($category !== "เมนูทั้งหมด"): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card p-3 h-100">
                                                <label class="form-label fw-bold text-dark"><?php echo ucfirst($category); ?></label>
                                                <div>
                                                    <input type="checkbox" id="<?php echo $category; ?>SelectAll" class="form-check-input" onchange="toggleAllCheckboxes(this, '.<?php echo $category; ?>Checkbox')">
                                                    <label for="<?php echo $category; ?>SelectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                                </div>
                                                <?php foreach ($items as $item): ?>
                                                    <div>
                                                        <input type="checkbox" class="<?php echo $category; ?>Checkbox form-check-input menuCheckbox" id="<?php echo $item; ?>" value="<?php echo $item; ?>" onchange="updateSelectAll('.<?php echo $category; ?>Checkbox', '<?php echo $category; ?>SelectAll')">
                                                        <label for="<?php echo $item; ?>" class="form-check-label text-dark"><?php echo $item; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="button" class="btn btn-primary" onclick="applyFilters()">ยืนยัน</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- โต๊ะ -->
            <div class="dropdown mb-4">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    เลือกโต๊ะ
                </button>
                <div class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="tableSelectAll" onchange="toggleAllTables(this)">
                        <label class="form-check-label" for="tableSelectAll">เลือกทั้งหมด</label>
                    </div>
                    <?php for ($i = 1; $i <= 20; $i++): ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input tableCheckbox" id="table<?php echo $i; ?>" value="โต๊ะ <?php echo $i; ?>" onchange="updateSelectedTables()">
                            <label class="form-check-label text-dark" for="table<?php echo $i; ?>">โต๊ะ <?php echo $i; ?></label>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <p id="selectedTables" class="mb-4 text-dark">โต๊ะที่เลือก: ไม่มี</p>

            <!-- summary display -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="totalPriceDisplay" class="form-label fw-bold">ยอดขายรวมทั้งหมด:</label>
                        <label id="totalPriceDisplay" class="form-label">0.00บาท</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="totalCustomersDisplay" class="form-label fw-bold">จำนวนลูกค้าทั้งหมด:</label>
                        <label id="totalCustomersDisplay" class="form-label">0</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="bestSellingPackage" class="form-label fw-bold">แพ็คเกจที่ขายดีที่สุด:</label>
                        <label id="bestSellingPackage" class="form-label">#</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="topRevenueTable" class="form-label fw-bold">โต๊ะที่สร้างรายได้สูงสุด:</label>
                        <label id="topRevenueTable" class="form-label">#</label>
                    </div>
                </div>
            </div>

            <!-- Table Display -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table--bs-secondary-bg">
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

        function applyFilters() {
            // Collect all filters and apply them
            const selectedDate = document.getElementById('dateSelect').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const gender = document.getElementById('genderSelect').value;
            const age = document.getElementById('ageSelect').value;
            const religion = document.getElementById('religionSelect').value;
            const member = document.getElementById('memberSelect').value;
            const table = document.getElementById('tableSelect').value;

            filterData();

            const filterModal = document.getElementById('filterModal');
            const modalInstance = bootstrap.Modal.getInstance(filterModal);
            modalInstance.hide();
        }

        function toggleCategory(selectedCategory) {
            // Toggle the selected category
            if (selectedCategory === "ทั้งหมด") {
                activeCategories = ["ทั้งหมด"];
            } else {
                if (activeCategories.includes(selectedCategory)) {
                    activeCategories = activeCategories.filter(cat => cat !== selectedCategory); // Remove if already selected
                } else {
                    if (activeCategories.includes("all")) {
                        activeCategories = [selectedCategory]; // Replace "ทั้งหมด" with specific categories
                    } else {
                        activeCategories.push(selectedCategory); // Add new category
                    }
                }
            }

            updateCategoryButtons();
            filterData();
        }

        function toggleAllMenus() {
            const selectAllCheckbox = document.getElementById('selectAllMenus');
            const menuCheckboxes = document.querySelectorAll('.menuCheckbox');

            menuCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Update "ทั้งหมด" ในแต่ละ category
            const categorySelectAllCheckboxes = document.querySelectorAll('input[id$="SelectAll"]');
            categorySelectAllCheckboxes.forEach(categoryCheckbox => {
                categoryCheckbox.checked = selectAllCheckbox.checked;
                categoryCheckbox.indeterminate = false;
            });
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
                const matchesTable = (selectedTable === 'เลือกทั้งหมด' || row.classList.contains(selectedTable)); // ตรวจสอบโต๊ะ
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

        const dateList = [];

        function addDate() {
            const dateInput = document.getElementById('dateSelect');
            const selectedDate = dateInput.value;

            if (selectedDate) {
                dateList.push(selectedDate);
                updateDateList();
                dateInput.value = ''; // ล้างค่า input หลังจากเพิ่ม
            } else {
                alert("กรุณาเลือกวันที่ก่อน");
            }
        }

        function updateDateList() {
            const listElement = document.getElementById('dateList');
            listElement.innerHTML = ''; // ล้างรายการก่อน

            dateList.forEach((date, index) => {
                const dateItem = document.createElement('div');
                dateItem.className = 'date-item';
                dateItem.textContent = date;

                // สร้างปุ่มกากบาท
                const removeButton = document.createElement('button');
                removeButton.textContent = '✖';
                removeButton.className = 'btn btn-danger btn-sm ms-2';
                removeButton.onclick = () => removeDate(index); // กำหนดฟังก์ชันลบวันที่

                dateItem.appendChild(removeButton);
                listElement.appendChild(dateItem);
            });

            // แสดงวันที่ที่เลือกใน span
            document.getElementById('selectedDates').textContent = dateList.join(', ');
        }

        function removeDate(index) {
            dateList.splice(index, 1); // ลบวันที่ที่เลือก
            updateDateList(); // อัปเดตรายการ
        }

        const timeRanges = [];

        function addTimeRange() {
            const startTimeInput = document.getElementById('startTime');
            const endTimeInput = document.getElementById('endTime');
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;

            if (startTime && endTime) {
                timeRanges.push({
                    start: startTime,
                    end: endTime
                });
                updateTimeList();
                startTimeInput.value = ''; // ล้างค่า input หลังจากเพิ่ม
                endTimeInput.value = ''; // ล้างค่า input หลังจากเพิ่ม
            } else {
                alert("กรุณาเลือกช่วงเวลาเริ่มต้นและสิ้นสุด");
            }
        }

        function updateTimeList() {
            const listElement = document.getElementById('timeList');
            listElement.innerHTML = ''; // ล้างรายการก่อน

            timeRanges.forEach((range, index) => {
                const timeItem = document.createElement('div');
                timeItem.className = 'time-item';
                timeItem.textContent = `เริ่ม: ${range.start} - สิ้นสุด: ${range.end}`;

                // สร้างปุ่มกากบาท
                const removeButton = document.createElement('button');
                removeButton.textContent = '✖';
                removeButton.className = 'btn btn-danger btn-sm ms-2';
                removeButton.onclick = () => removeTimeRange(index); // กำหนดฟังก์ชันลบช่วงเวลา

                timeItem.appendChild(removeButton);
                listElement.appendChild(timeItem);
            });
        }

        function removeTimeRange(index) {
            timeRanges.splice(index, 1); // ลบช่วงเวลาที่เลือก
            updateTimeList(); // อัปเดตรายการ
        }

        function toggleAllCheckboxes(selectAllCheckbox) {
            const checkboxes = document.querySelectorAll('.genderCheckbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked; // ตั้งค่า checkbox อื่นๆ ตามสถานะของ checkbox "ทั้งหมด"
            });
        }

        function updateSelectAll(checkboxClass, selectAllId) {
            const checkboxes = document.querySelectorAll(checkboxClass);
            const selectAllCheckbox = document.getElementById(selectAllId);
            selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        }

        function toggleAllCheckboxes(selectAllCheckbox, checkboxClass) {
            const checkboxes = document.querySelectorAll(checkboxClass);
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        function toggleAllTables(selectAllCheckbox) {
            const tableCheckboxes = document.querySelectorAll('.tableCheckbox');
            tableCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedTables();
        }

        function updateSelectedTables() {
            const selectedTables = [];
            const tableCheckboxes = document.querySelectorAll('.tableCheckbox');

            tableCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedTables.push(checkbox.value);
                }
            });

            const selectedTablesDisplay = document.getElementById('selectedTables');
            if (selectedTables.length > 0) {
                selectedTablesDisplay.textContent = `โต๊ะที่เลือก: ${selectedTables.join(', ')}`;
            } else {
                selectedTablesDisplay.textContent = 'โต๊ะที่เลือก: ไม่มี';
            }

            // อัปเดตสถานะ "เลือกทั้งหมด" ตามการเลือก
            const selectAllCheckbox = document.getElementById('tableSelectAll');
            selectAllCheckbox.checked = selectedTables.length === tableCheckboxes.length;
        }
    </script>
    </body>

    </html>