<?php
  include('dbconnect.php');
  include('staffRecord.php');
  include('addUser.php');
  session_start();
  if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
  }

  $email = $_SESSION['email'];

  $sql = "SELECT username, role FROM accounts WHERE email = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("s",$email);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $role = $row['role'];
  }
  else{
    $username = "Unknown User";
  }
  // customer Info
  $sql1 = "SELECT * FROM customerinfo";
  $result1 = $con->query($sql1);
  if (!$result1) {
    die("Query failed: " . mysqli_error($con));
  }
  // customer counts
  $sql2 = "SELECT COUNT(*) as totalCustomer FROM customerinfo";
  $result2 = $con->query($sql2);
  if (!$result2) {
    die("Query failed: " . mysqli_error($con));
  }

  $row2 = $result2->fetch_assoc();
  $totalCustomer = $row2['totalCustomer'];

  // total staff
  $sqlStaff = "SELECT COUNT(*) as totalStaff FROM staffrecords";
  $staffCountResult = $con->query($sqlStaff);
  if (!$staffCountResult) {
    die("Query failed: " . mysqli_error($con));
  }
  $staffCountRow = $staffCountResult->fetch_assoc();
  $totalStaff = $staffCountRow['totalStaff'] ?? 0;

  // total rentals today
  $rentalCountSql = "SELECT COUNT(*) as totalRentals FROM rental where DATE(addedDate) = CURDATE()";
  $rentalCountResult = $con->query($rentalCountSql);
  if (!$rentalCountResult) {
    die("Query failed: " . mysqli_error($con));
  }
  $rentalRow = $rentalCountResult->fetch_assoc();
  $totalRentals = $rentalRow['totalRentals'];


  //rental info
  $sql3 = "SELECT r.rentalID, c.customerID, CONCAT(c.firstName, ' ', c.lastName) AS fullName, c.contact,
          r.carType, r.ratePerDay, r.numberOfDays, r.total, r.dateStart, CONCAT(DATE_FORMAT(r.dateStart, '%b %d, %Y'), ' - ', DATE_FORMAT(r.dateEnd, '%b %d, %Y')) AS dateDuration, 
          DATE_FORMAT(r.addedDate, '%b %d, %Y') AS addedDateWord ,r.addedBy
        FROM rental r JOIN customerinfo c ON r.customerID = c.customerID ORDER BY r.dateStart DESC";
  $result3 = $con->query($sql3);
  if (!$result3) {
    die("Query failed: " . mysqli_error($con));
  }

  $sql4 = "SELECT * FROM staffrecords";
  $staffResult = $con->query($sql4);
  if (!$staffResult) {
    die("Query failed: " . mysqli_error($con));
  }

  // Dashboard Sales today
  $salesSql = "SELECT SUM(total) as totalSales FROM rental WHERE DATE(addedDate) = CURDATE()";
  $salesResult = $con->query($salesSql);
  if (!$salesResult) {
    die("Query failed: " . mysqli_error($con));
  }
  $salesRow = $salesResult->fetch_assoc();
  $totalSales = $salesRow['totalSales'] ?? 0;


  //  add user
  $sql5 = "SELECT * FROM accounts WHERE email != '$email'";
  $result5 = $con->query($sql5);
  if (!$result5) {
    die("Query failed: " . mysqli_error($con));
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Car Rental POS</title>
  <link rel="stylesheet" href="../css/homepage.css">

  <!-- Para mapasa ko yung role varianle sa js -->
  <script>const userRole = "<?php echo $role; ?>";</script>
  
  <script src="../js/address.js" defer></script>
</head>

<body>

    <!-- SIDE NAV NA POSA -->
    <aside class="sidebar">
      <h2>Car Rental POS</h2>

      <div class="user-info">
        <img src="../assets/pfp.jpg" alt="pfp" class="pfp">
        <p><?php echo htmlspecialchars($role) ?> <?php echo htmlspecialchars($username) ?></p>
      </div>
      <nav>
        <ul>
          <li id="dashboard_button" class="navItem active">Dashboard</li>
          <li id="customers_button" class="navItem">Add Customers</li>
          <li id="rentals_button" class="navItem">Add Rentals</li>
          <li id="staff_button" class="navItem">Staff</li>
          <li id="reports_button" class="navItem">Daily Sales</li>
          <li id="users_button" class="navItem">Add Users</li>
          <li id="settings_button" class="navItem">Change Password</li>
        </ul>
      </nav>
      <div class="Logout">
        <form action="logout.php">
          <button type="submit" class="LogOutBtn">Log Out</button>
        </form>
      </div>
    </aside>


  <!-- Main Content -->
    <main class="main-content" id="dashboardSection">
      <header>
        <h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>
        <p>Here's what's happening today.</p>
      </header>

      <section class="cards">
        <div class="card daily-sales">
          <h3>Total Customers</h3>
          <p><?php echo htmlspecialchars($totalCustomer) ?></p>
        </div>
        <div class="card">
          <h3>Total Staff</h3>
          <p><?php echo htmlspecialchars($totalStaff) ?></p>
        </div>
        <div class="card">
          <h3>Rentals Today</h3>
          <p><?php echo htmlspecialchars($totalRentals) ?></p>
        </div>
        <div class="card">
          <h3>Sales Today</h3>
          <p><?php echo'₱'; echo  htmlspecialchars($totalSales) ?></p> 
        </div>
      </section>
    </main>

  <!-- PARA SA CUSTOMER RECORD -->
  <div class="section" id="recordCustomer">
    <main class="main-content" id="dashboardSection">
      <header class="dashboard-header">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>
            <p>Here's what's happening today.</p>
        </div>

        <div class="add-customer" id="openAddCustomerModal">
            <span>Add Customer</span>
            <img src="../assets/icons/plus-solid.svg" alt="add icon" class="add-icon">
        </div>
                

        <!-- Add Customer Modal -->
        <div id="addCustomerModal" class="modal">
          <div class="modal-content">
            <div class="upperPosition">
              <div><h2 style="margin-top:0;">Customer Information</h><br></div>
              <div><span id="closeAddCustomerModal">&times;</span></div>
            </div>
            <form action="addCustomerInfo.php" method="POST">
              <div class="name">
                <div class="nameGroup">
                  <label for="Last Name">Last Name</label><br>
                  <input type="text" id="lastName" name="lastName" class="form-input">
                </div>
                <div class="nameGroup">
                  <label for="First Name">First Name</label><br>
                  <input type="text" id="firstName" name="firstName" class="form-input">
                </div>
                <div class="nameGroup middleName">
                  <label for="Middle Name">Middle Name</label><br>
                  <input type="text" id="middleName" name="middleName">
                </div>
              </div>


              <div>
                <h2 style="margin-top:0;">Address</h2><br>
              </div>

              <div class="addressDiv">
                <div class="addressgroup">
                  <label for="Province">Province</label><br>
                  <select id="province" name="province"></select>
                </div>
                <div class="addressgroup">
                  <label for="City/Municipality">City/Municipality</label><br>
                  <select id="city" name="city"></select>
                </div>
                <div class="addressgroup">
                  <label for="Barangay">Barangay</label><br>
                  <select id="barangay" name="barangay"></select>
                </div>
              </div>
              <div class="detailedAdd">
                  <div>
                    <label>Detailed Address</label><br>
                    <input type="text" name="detailedAdd" id="detailedAdd">
                  </div>
                  <div>
                    <label>Contact Number</label><br>
                    <input type="text" name="contact" id="contact">
                  </div>
 
              </div>
              <div class="modalButtons">
                <button type="submit" class="add-btn">Add</button>
                <button id="cancelBTN" type="button">Cancel</button>
              </div>
              
            </form>
        </div>


        <!-- TABLE -->
      </header>
        <div id="customerList">
          <h2>Customer List</h2>
          <input type="text" id="searchCustomer1" placeholder="Search by name or contact...">
          <?php
           if ($result1->num_rows > 0) {
            echo '
              <div class="tableDiv">
                <table>
                  <thead>
                      <tr>
                        <th>Customer ID</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Detailed Address</th>
                        <th>Phone Number</th>
                        <th>Added By</th>
                        <th class="actionsTD" >Actions</th>
                      </tr>
                  </thead>
                  <tbody id="customerTableBody1">
                ';
                while ($row = $result1->fetch_assoc()) {
                    echo '
                      <tr>
                          <td>' . htmlspecialchars($row['customerID']) . '</td>
                          <td>' . htmlspecialchars($row['LastName'] . ', ' . $row['FirstName'] . ' ' . $row['MiddleName']) . '</td>
                          <td>' . htmlspecialchars($row['province'] . ', ' . $row['city'] . ', ' . $row['barangay']) . '</td>
                          <td>' . htmlspecialchars($row['detailedAddress']) . '</td>
                          <td>' . htmlspecialchars($row['contact']) . '</td>
                          <td>' . htmlspecialchars($row['addedBy']) . '</td>
                          <td class="actionsTD">
                            <div class="actionsDiv">
                              <div>
                                  <button type="button" class="actionBtn editBtn"
                                    data-id="' . htmlspecialchars($row['customerID']) . '"
                                    data-lname="' . htmlspecialchars($row['LastName']) . '"
                                    data-fname="' . htmlspecialchars($row['FirstName']) . '"
                                    data-mname="' . htmlspecialchars($row['MiddleName']) . '"
                                    data-province="' . htmlspecialchars($row['province']) . '"
                                    data-city="' . htmlspecialchars($row['city']) . '"
                                    data-barangay="' . htmlspecialchars($row['barangay']) . '"
                                    data-detailed="' . htmlspecialchars($row['detailedAddress']) . '"
                                    data-contact="' . htmlspecialchars($row['contact']) . '">
                                    <img src="../assets/icons/edit.png" alt="edit">
                                </button>
                              </div>
                              <div>
                                <form action="deleteCustomer.php" method="POST" onsubmit="return confirm(\'Are you sure you want to remove this record?\');">
                                  <input type="hidden" name="DelID" value="' . htmlspecialchars($row['customerID']) . '">
                                  <button type="submit" class="actionBtn">
                                    <img src="../assets/icons/delete.png" alt="delete" class="deleteBtn">
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                      </tr>
                  ';
              }
              echo '
                    </tbody>
                  </table>
                  </div>
                  ';
            } else {
                echo '<p class="noRecords">No records found.</p>';
            }

          ?>
          <div id="editModal" class="modal" style="display:none;">
            <div class="modal-content">
               <div class="upperPosition">
                <div><h2 style="margin-top:0;">Edit Customer</h><br></div>
                <div><span class="closeModal">&times;</span></div>
            </div>
              <form id="editForm" method="POST" action="editCustomer.php">
                <input type="hidden" name="customerID" id="editID">
                <div class="name">
                <div class="nameGroup">
                  <label>Last Name</label>
                  <input type="text" name="lastName" id="editLastName">
                </div>
                <div class="nameGroup">
                  <label>First Name: <input type="text" name="firstName" id="editFirstName"></label>
                </div>
                <div class="nameGroup middleName">
                  <label>Middle Name: <input type="text" name="middleName" id="editMiddleName"></label>
                </div>
              </div>

              <div>
                <h2 style="margin-top:0;">Address</h2><br>
              </div>

              <div class="addressDiv">
                <div class="addressgroup">
                  <label>Province: <input type="text" name="province" id="editProvince"></label>
                </div>
                <div class="addressgroup">
                  <label>City: <input type="text" name="city" id="editCity"></label>
                </div>
                <div class="addressgroup">
                  <label>Barangay: <input type="text" name="barangay" id="editBarangay"></label>
                </div>
              </div>
              <div class="detailedAdd">
                  <div>
                    <label>Detailed Address: <input type="text" name="detailedAdd" id="editDetailed"></label>
                  </div>
                  <div>
                    <label>Contact: <input type="text" name="contact" id="editContact"></label>
                  </div>
 
              </div>
              <div class="modalButtons">
                <button type="submit">Save Changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>
    </main>
    </div>
  </div>




  
<!-- PARA SA RENTALS -->
  <div class="section" id="salesSection">
    <main class="main-content">
      <header class="dashboard-header">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>
            <p>Here's what's happening today.</p>
        </div>
        <div class="add-customer" id="openAddRentals">
            <span>Add Rentals</span>
            <img src="../assets/icons/plus-solid.svg" alt="add icon" class="add-icon">
        </div>        

        <!-- Add Rental Modal -->
        <div id="addRentalsModal" class="modal">
          <div class="modal-content">
            <div class="upperPosition">
              <div><h2 style="margin-top:0;">Rent Information</h><br></div>
              <div><span id="closeRentInformation">&times;</span></div>
            </div>
            <form action="InsertRental.php" method="POST" id="rentalFormId">
              <div class="customerSelection">
                <div class="labelDiv">
                  <label>Customer ID</label>
                  <input type="text" id="selectedCustomerID" name="customerID" readonly required>
                </div>
                <div class="labelDiv">
                  <label>Full Name</label>
                  <input type="text" id="selectedCustomerName" readonly required>
                </div>
                <div class="labelDiv">
                  <label>Contact</label>
                <input type="text" id="selectedCustomerContact" readonly required>
                </div>
                
              </div>
              <div class="customerSelection">
                  <button type="button" id="openCustomerModal">Choose Customer</button>
                </div>
              <div>
                <h2 style="margin-top:0;">Select Car & Dates</h2><br>
              </div>
              <div class="cargroup">
                <div>
                  <label for="carType">Car Type</label><br>
                  <select id="carType" name="carType" class="form-input" required>
                    <option value="" disabled selected>Select car type</option>
                    <option value="SUV">SUV</option>
                    <option value="SEDAN">Sedan</option>
                    <option value="VAN">Van</option>
                  </select>
                </div>
                <div>
                  <label for="rate">Rate per day</label><br>
                  <input type="number" step="0.01" id="rate" name="rate" required>
                </div>
              </div>
              
              <div class="totalgroup">
                <div>
                  <label for="Number of Days">Number of Days</label><br>
                  <input type="number" step="1" id="numberOfDays" name="numberOfDays" required>
                </div>
                <div>
                  <label for="total">Total</label><br>
                  <input type="text" id="totalDisplay" disabled readonly>
                  <input type="hidden" name="total" id="total">
                </div>
              </div>
              <div class="dates">
                <div class="dategroup">
                  <label for="dateStart">Date Start</label><br>
                  <input type="date" id="dateStart" name="dateStart" required>
                </div>
                <div class="dategroup">
                  <label for="dateEnd">Date End</label><br>
                  <input type="date" id="dateEnd" name="dateEnd" disabled readonly>
                  <input type="hidden" id="dateEnd1" name="dateEnd1"> 
                </div>
              </div>
              <div class="modalButtons">
                <button type="submit" class="add-btn">Add</button>
                <button id="cancelRent" type="button">Cancel</button>
              </div>
            </form>
        </div>
        <!-- Select Customer -->
        <div id="selectCustomerModal" class="modal selectModal">
          <div class="modal-content">
            <div class="selectCustomerTop">
              <h2>Select Customer</h2>
              <span class="close" id="closeCustomerModal">&times;</span>
            </div>
            <input type="text" id="searchCustomer" placeholder="Search by name or contact...">
            <div class="selectTable">
              <table>
                <thead>
                  <tr>
                    <th>Customer ID</th>
                    <th>Full Name</th>
                    <th>Contact</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="customerTableBody">
                  <?php
                    $result = $con->query("SELECT customerID, FirstName, MiddleName, LastName, contact FROM customerinfo");
                    while ($row = $result->fetch_assoc()) {
                      $fullName = htmlspecialchars($row['LastName'] . ', ' . $row['FirstName'] . ' ' . $row['MiddleName']);
                      echo '
                        <tr>
                          <td>' . htmlspecialchars($row['customerID']) . '</td>
                          <td>' . $fullName . '</td>
                          <td>' . htmlspecialchars($row['contact']) . '</td>
                          <td><button type="button" onclick="selectCustomer(' . $row['customerID'] . ', \'' . $fullName . '\', \'' . $row['contact'] . '\')">Select</button></td>
                        </tr>
                      ';
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </header>
      <!-- TABLE -->
        <div id="rentalList">
          <h2>Rental List</h2>
          <input type="text" id="searchCustomer2" placeholder="Search by name or contact...">
          <?php
           if ($result3->num_rows > 0) {
            echo '
              <div class="tableDiv">
                <table>
                  <thead>
                      <tr>
                        <th>Rental ID</th>
                        <th>Full Name</th>
                        <th>Car Type</th>
                        <th>Rate Per Day</th>
                        <th>Number of Days</th>
                        <th>Total</th>
                        <th>Rent Duration</th>
                        <th>Added by</th>
                        <th>Added Date</th>
                        <th class="actionsTD" >Actions</th>
                      </tr>
                  </thead>
                  <tbody id="customerTableBody2">
                ';
                while ($row3 = $result3->fetch_assoc()) {
                    echo '
                      <tr>
                          <td>' . htmlspecialchars($row3['rentalID']) . '</td>
                          <td>' . htmlspecialchars($row3['fullName']) . '</td>
                          <td>' . htmlspecialchars($row3['carType']) . '</td>
                          <td>' . htmlspecialchars($row3['ratePerDay']) . '</td>
                          <td>' . htmlspecialchars($row3['numberOfDays']) . '</td>
                          <td>' . htmlspecialchars($row3['total']) . '</td>
                          <td>' . htmlspecialchars($row3['dateDuration']) . '</td>
                          <td>' . htmlspecialchars($row3['addedBy']) . '</td>
                          <td>' . htmlspecialchars($row3['addedDateWord']) . '</td>
                          <td class="actionsTD">
                            <div class="actionsDiv">
                              <div class="actionsDiv">
                                <div>
                                  <button type="button" class="actionBtn editRentalBtn"
                                  data-id="' . htmlspecialchars($row3["rentalID"]) . '"
                                      data-customerid="' . $row3['customerID'] . '"
                                      data-name="' . htmlspecialchars($fullName) . '"
                                      data-contact="' . $row3['contact'] . '"
                                      data-cartype="' . $row3['carType'] . '"
                                      data-rate="' . $row3['ratePerDay'] . '"
                                      data-days="' . $row3['numberOfDays'] . '"
                                      data-datestart="' . $row3['dateStart'] . '">
                                    <img src="../assets/icons/edit.png" alt="edit">
                                  </button>
                                </div>
                                <div>
                                  <form action="deleteRental.php" method="POST" onsubmit="return confirm(\'Are you sure you want to remove this record?\');">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($row3['rentalID']) . '">
                                    <button type="submit" class="actionBtn">
                                      <img src="../assets/icons/delete.png" alt="delete" class="deleteBtn">
                                    </button>
                                  </form>
                                </div>
                            </div>
                          </td>
                      </tr>
                    ';
                }
                echo '
                  </tbody>
                </table>
              </div>
                ';
                
            } else {
                echo '<p class="noRecords">No records found.</p>';
            }

          ?>

          <!-- edit Rental Modal -->
        <div id="EditRentalsModal" class="modal" style="display:none;">
          <div class="modal-content">
            <div class="upperPosition">
              <div><h2 style="margin-top:0;">Edit Rent Information</h><br></div>
              <div><span id="closeRentEditInformation">&times;</span></div>
            </div>
            <form action="updateRental.php" method="POST" id="EditRentalFormId">
              <input type="hidden" name="rentalID" id="editRentalID">
              <div class="customerSelection">
                <div class="labelDiv">
                  <label>Customer ID</label>
                  <input type="text" id="EditselectedCustomerID" name="customerID" readonly required>
                </div>
                <div class="labelDiv">
                  <label>Full Name</label>
                  <input type="text" id="EditselectedCustomerName" readonly required>
                </div>
                <div class="labelDiv">
                  <label>Contact</label>
                <input type="text" id="EditselectedCustomerContact" readonly required>
                </div>
                
              </div>
              <div class="customerSelection">
                  <button type="button" id="EditopenCustomerModal">Choose Customer</button>
                </div>
              <div>
                <h2 style="margin-top:0;">Select Car & Dates</h2><br>
              </div>
              <div class="cargroup">
                <div>
                  <label for="carType">Car Type</label><br>
                  <select id="EditcarType" name="carType" class="form-input">
                    <option value="" disabled selected>Select car type</option>
                    <option value="SUV">SUV</option>
                    <option value="SEDAN">Sedan</option>
                    <option value="VAN">Van</option>
                  </select>
                </div>
                <div>
                  <label for="rate">Rate per day</label><br>
                  <input type="number" step="0.01" id="Editrate" name="rate" required>
                </div>
              </div>
              
              <div class="totalgroup">
                <div>
                  <label for="Number of Days">Number of Days</label><br>
                  <input type="number" step="1" id="EditnumberOfDays" name="numberOfDays" required>
                </div>
                <div>
                  <label for="total">Total</label><br>
                  <input type="text" id="EditTotalDisplay" disabled readonly>
                  <input type="hidden" name="total" id="EditTotal">
                </div>
              </div>
              <div class="dates">
                <div class="dategroup">
                  <label for="dateStart">Date Start</label><br>
                  <input type="date" id="EditdateStart" name="dateStart">
                </div>
                <div class="dategroup">
                  <label for="dateEnd">Date End</label><br>
                  <input type="date" id="EditdateEnd" name="dateEnd" disabled readonly>
                  <input type="hidden" id="EditdateEnd1" name="dateEnd1">
                </div>
              </div>
              <div class="modalButtons">
                <button type="submit" class="add-btn">Update</button>
                <button id="rentcancelRent" type="button">Cancel</button>
              </div>
            </form>
        </div>
        <!-- Edit Select Customer -->
        <div id="EditselectCustomerModal" class="modal selectModal">
          <div class="modal-content">
            <div class="selectCustomerTop">
              <h2>Select Customer</h2>
              <span class="close" id="EditcloseCustomerModal">&times;</span>
            </div>
            <input type="text" id="EditsearchCustomer" placeholder="Search by name or contact...">
            <div class="selectTable">
              <table>
                <thead>
                  <tr>
                    <th>Customer ID</th>
                    <th>Full Name</th>
                    <th>Contact</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="EditcustomerTableBody3">
                  <?php
                    $result = $con->query("SELECT customerID, FirstName, MiddleName, LastName, contact FROM customerinfo");
                    while ($row = $result->fetch_assoc()) {
                        $fullName = htmlspecialchars($row['LastName'] . ', ' . $row['FirstName'] . ' ' . $row['MiddleName']);
                        echo '
                          <tr>
                              <td>' . htmlspecialchars($row['customerID']) . '</td>
                              <td>' . $fullName . '</td>
                              <td>' . htmlspecialchars($row['contact']) . '</td>
                              <td><button type="button" onclick="selectEditCustomer(' . $row['customerID'] . ', \'' . $fullName . '\', \'' . $row['contact'] . '\')">Select</button></td>
                          </tr>
                      ';
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </div>
    </main>
  </div>

<!-- PARA SA STAFF RECORD -->
<div class="section" id="recordStaff">
  <main class="main-content" id="dashboardSection">
    <header class="dashboard-header">
      <div class="welcome-section">
          <h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>
          <p>Here's what's happening today.</p>
      </div>

      <div class="add-customer addStaff" id="openAddStaffModal">
          <span>Add Staff</span>
          <img src="../assets/icons/plus-solid.svg" alt="add icon" class="add-icon">
      </div>

      <!-- add staff modal -->
      <div class="modal" id="addStaffModal">
        <div class="modal-content">
         <div class="upperPosition">
            <div><h2 style="margin-top:0;">Add Staff</h2></div>
            <div><span id="closeAddStaffModal">&times;</span></div>
          </div>
          <form action="staffRecord.php" method="POST">
            <label for="id">Staff ID</label><br>
            <input type="text" value="<?php echo $generatedId; ?>" disabled readonly><br>
            <input type="hidden" name="id" value="<?php echo $generatedId; ?>">

            <label for="LastName">Last Name</label><br>
            <input type="text" id="LastName" name="LastName" required><br>

            <label for="FirstName">First Name</label><br>
            <input type="text" id="FirstName" name="FirstName" required><br>

            <label for="MiddleInitial">Middle Initial</label><br>
            <input type="text" id="MiddleInitial" name="MiddleInitial"><br>

            <label for="Address">Address</label><br>
            <input type="text" id="Address" name="Address" required><br>

            <label for="ContactNumber">Contact Number</label><br>
            <input type="text" id="ContactNumber" name="ContactNumber" required><br>

            <label for="Salary">Monthly Salary</label><br>
            <input type="number" id="Salary" name="Salary" required><br>

            <div class="modalButtons">
              <button type="submit" class="add-btn">Add</button>
              <button id="cancelStaffBTN" type="button">Cancel</button>
            </div>
          </form>         
        </div>          
      </div>
    </header>
    <!-- TABLE -->
    <div id="staffList">
      <h2>Staff List</h2>
      <input type="text" id="searchStaff" placeholder="Search by name or contact...">

      <?php
        if ($staffResult->num_rows > 0) {
          echo '
            <div class="tableDiv">
              <table>
                <thead>
                    <tr>
                      <th>Staff ID</th>
                      <th>Full Name</th>
                      <th>Address</th>
                      <th>Contact Number</th>
                      <th>Monthly Salary</th>
                      <th class="actionsTD">Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTableBody">
          ';
          while ($row = $staffResult->fetch_assoc()) {
              echo '
                <tr>
                    <td>' . htmlspecialchars($row['id']) . '</td>
                    <td>' . htmlspecialchars($row['LastName'] . ', ' . $row['FirstName'] . ' ' . $row['MiddleInitial']) . '</td>
                    <td>' . htmlspecialchars($row['Address']) . '</td>
                    <td>' . htmlspecialchars($row['ContactNumber']) . '</td>
                    <td>' . htmlspecialchars($row['Salary']) . '</td>
                    <td class="actionsTD">
                      <div class="actionsDiv">
                        <div>
                            <button type="button" class="actionBtn editStaffBtn"
                              data-id="' . htmlspecialchars($row['id']) . '"
                              data-lastname="' . htmlspecialchars($row['LastName']) . '"
                              data-firstname="' . htmlspecialchars($row['FirstName']) . '"
                              data-minit="' . htmlspecialchars($row['MiddleInitial']) . '"
                              data-address="' . htmlspecialchars($row['Address']) . '"
                              data-contact="' . htmlspecialchars($row['ContactNumber']) . '"
                              data-salary="' . htmlspecialchars($row['Salary']) . '">
                              <img src="../assets/icons/edit.png" alt="edit">
                          </button>
                        </div>
                        <div>
                          <form action="deleteStaff.php" method="POST" onsubmit="return confirm(\'Are you sure you want to remove this record?\');">
                            <input type="hidden" name="DelID" value="' . htmlspecialchars($row['id']) . '">
                            <button type="submit" class="actionBtn">
                              <img src="../assets/icons/delete.png" alt="delete" class="deleteBtn">
                            </button>
                          </form>
                        </div>
                      </div>
                    </td>
                </tr>
              ';
          }
          echo '
                </tbody>
              </table>
            </div>
          ';
        } else {
          echo '<p>No staff records found.</p>';
        }
      ?>

      <!-- edit staff Modal -->
      <div id="editStaffModal" class="modal" style="display:none;">
        <div class="modal-content">
          <div class="upperPosition">
            <div><h2 style="margin-top:0;">Edit Staff</h2></div>
            <div><span class="closeEditStaffModal">&times;</span></div>
          </div>
          <form id="editStaffForm" method="POST" action="updateStaff.php">
            <input type="hidden" name="id" id="editStaffID">
            <label for="LastName">Last Name</label><br>
            <input type="text" id="editStaffLastName" name="LastName" required><br>

            <label for="FirstName">First Name</label><br>
            <input type="text" id="editStaffFirstName" name="FirstName" required><br>

            <label for="MiddleInitial">Middle Initial</label><br>
            <input type="text" id="editMiddleInitial" name="MiddleInitial"><br>

            <label for="Address">Address</label><br>
            <input type="text" id="editAddress" name="Address" required><br>

            <label for="ContactNumber">Contact Number</label><br>
            <input type="text" id="editContactNumber" name="ContactNumber" required><br>

            <label for="Salary">Monthly Salary</label><br>
            <input type="number" id="editSalary" name="Salary" required><br>

            <div class="modalButtons">
              <button type="submit">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>


  <!-- PARA SA DAILY SALES -->
  <div class="section" id="reportSection">
    <main class="main-content" id="dashboardSection">
      <header class="dashboard-header">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>
            <p>Here's what's happening today.</p>
        </div>
      </header>
      <div id="dailySales">
        <h2>Daily Report</h2>
        <div class="Sales">
          <div class="card" id="daily-rentals">
            <h3>Total Renters</h3>
            <p id="rentals-count">0</p>
          </div>

          <div class="card" id="daily-sales">
            <h3>Sales</h3>
            <p id="sales-amount">₱0</p>
          </div>

          <div class="date-selection">
            <form id="dateFilterForm">
              <div class="SelectDate">
                <input type="date" id="dateSelection" name="dateSelection" required>
                <button type="submit">Select</button>
              </div>
            </form>
          </div>
        </div>             
      </div>
    </main>
  </div>

  <!-- PARA SA ADDING NG USER OR ADMIN -->
<div class="section" id="userSection">
  <main class="main-content" id="dashboardSection">
    <header class="dashboard-header">
      <div class="welcome-section">
        <h1>Welcome, <?php echo htmlspecialchars($username) ?></h1>
        <p>Here's what's happening today.</p>
      </div>

      <div class="add-customer" id="openAddUserModal">
        <span>Add New Staff/Admin</span>
        <img src="../assets/icons/plus-solid.svg" alt="add icon" class="add-icon">
      </div>
    </header>
    <!-- You can add your user/admin table or content here -->
    <!-- table -->
            <div id="customerList">
          <h2>Staff and Admin List</h2>
          <input type="text" id="searchCustomer5" placeholder="Search by name or contact...">
          <?php
            if ($result5 && $result5->num_rows > 0) {
            echo '
              <div class="tableDiv">
                <table>
                  <thead>
                      <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Username</th>

                        <th class="actionsTD" >Actions</th>
                      </tr>
                  </thead>
                  <tbody id="customerTableBody5">
                ';
          while ($row5 = $result5->fetch_assoc()) {
              echo '
                <tr>
                    <td>' . htmlspecialchars($row5['id']) . '</td>
                    <td>' . htmlspecialchars($row5['email']) . '</td>
                    <td>' . htmlspecialchars($row5['role']) . '</td>
                    <td>' . htmlspecialchars($row5['username']) . '</td>
                    <td class="actionsTD">
                      <div class="actionsDiv">
                        <div>
                            <button type="button" class="actionBtn editUserBtn"
                              data-id="' . htmlspecialchars($row5['id']) . '"
                              data-email="' . htmlspecialchars($row5['email']) . '"
                              data-password="' . htmlspecialchars($row5['password']) . '"
                              data-role="' . htmlspecialchars($row5['role']) . '"
                              data-username="' . htmlspecialchars($row5['username']) . '">
                              <img src="../assets/icons/edit.png" alt="edit">
                          </button>
                        </div>
                        <div>
                          <form action="deleteUser.php" method="POST" onsubmit="return confirm(\'Are you sure you want to remove this record?\');">
                            <input type="hidden" name="DelID" value="' . htmlspecialchars($row5['id']) . '">
                            <button type="submit" class="actionBtn">
                              <img src="../assets/icons/delete.png" alt="delete" class="deleteBtn">
                            </button>
                          </form>
                        </div>
                      </div>
                    </td>
                </tr>
            ';
              }
              echo '
                    </tbody>
                  </table>
                  </div>
                  ';
            } else {
                echo '<p class="noRecords">No records found.</p>';
            }

          ?>

          
          <!-- Add User Modal -->
  <div id="addUserModal" class="modal">
    <div class="modal-content">
      <div class="upperPosition">
        <div><h2 style="margin-top:0;">Add New Staff/Admin</h2><br></div>
        <div><span id="closeAddUserModal">&times;</span></div>
      </div>
      <form action="addUser.php" method="POST">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="role">Role</label><br>
        <select id="role" name="role" required>
          <option value="Staff">Staff</option>
          <option value="Admin">Admin</option>
        </select><br>
        <div class="modalButtons">
          <button type="submit" class="add-btn">Add User</button>
          <button id="cancelAddUser" type="button">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit User Modal -->
<div id="editUserModal" class="modal">
  <div class="modal-content">
    <div class="upperPosition">
      <div><h2 style="margin-top:0;">Edit Staff/Admin</h2></div>
      <div><span class="closeUserModal">&times;</span></div>
    </div>
    <form action="updateUser.php" method="POST">
      <input type="hidden" id="editUserID" name="id">
      <label for="email">Email</label><br>
      <input type="email" id="editemail" name="email" required><br>
      <label for="editUsername">Username</label><br>
      <input type="text" id="editUsername" name="username" required><br>
      <input type="text" id="editPassword" name="password" hidden><br>
      <label for="editRole">Role</label><br>
      <select id="editRole" name="role" required>
        <option value="Staff">Staff</option>
        <option value="Admin">Admin</option>
      </select><br>
      <div class="modalButtons">
        <button type="submit" class="add-btn">Save Changes</button>
      </div>
    </form>
  </div>
</div>



    
  </main>
</div>




  

<!-- SCRIPT PARA SA BUONG HOMEPAGE NAKA BUKOD NG FILE PARA MAS ATTRACTIVE TINGNAN  -->
  <script src="../js/homepage.js" defer></script>
  <script src="../js/addModal.js" defer></script>

<div class="section" id="settingsSection" style="display:none;">
  <main class="main-content" id="dashboardSection">
    <h2>Settings</h2><br><br>
    <h2>Change Password</h2>
    <form id="changePasswordForm" action="changePassword.php" method="POST">
      <label for="oldPassword">Old Password</label>
      <input type="password" id="oldPassword" name="oldPassword" required>

      <label for="newPassword">New Password</label>
      <input type="password" id="newPassword" name="newPassword" required>

      <label for="confirmPassword">Confirm New Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" required>

      <button type="submit">Change Password</button>
    </form>
    <div id="changePasswordMessage"></div>
  </main>
</div>
<!-- KULANG PA PO NG MGA DESIGN KASI MAG LALABA PA PO AKO -->



<!-- DILI KO PA PO NAAYOS YUNG RECORD NA PART, DI KO ALAM KUNG PANO GAWIN -->


</body>
</html>
