document.addEventListener('DOMContentLoaded', () => {
  // ---------- ADD CUSTOMER MODAL ----------
  const openCustomerModalBtn = document.getElementById('openAddCustomerModal');
  const customerModal = document.getElementById('addCustomerModal');
  const closeCustomerModalBtn = document.getElementById('closeAddCustomerModal');
  const cancelCustomerBtn = document.getElementById('cancelBTN');

  if (openCustomerModalBtn) {
    openCustomerModalBtn.onclick = () => customerModal.style.display = 'flex';
  }
  if (closeCustomerModalBtn) {
    closeCustomerModalBtn.onclick = () => customerModal.style.display = 'none';
  }
  if (cancelCustomerBtn) {
    cancelCustomerBtn.onclick = (e) => {
      e.preventDefault();
      customerModal.style.display = 'none';
    };
  }

    // Customer Search
    const searchInput1 = document.getElementById('searchCustomer1');

    
  if (searchInput1) {
    searchInput1.addEventListener('input', function () {
      const filter1 = this.value.toLowerCase();
      const rows1 = document.querySelectorAll('#customerTableBody1 tr');
      rows1.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter1) ? '' : 'none';
      });
    });
  }

  // ---------- RENTAL MODAL ----------
  const openRentalModal = document.getElementById('openAddRentals');
  const addRentalsModal = document.getElementById('addRentalsModal');
  const closeRentalModalBtn = document.getElementById('closeRentInformation');
  const cancelRentBtn = document.getElementById('cancelRent');

  


  if (openRentalModal) {
    openRentalModal.onclick = () => addRentalsModal.style.display = 'flex';
  }
  if (closeRentalModalBtn) {
    closeRentalModalBtn.onclick = () => addRentalsModal.style.display = 'none';
  }
  if (cancelRentBtn) {
    cancelRentBtn.onclick = (e) => {
      e.preventDefault();
      addRentalsModal.style.display = 'none';
    };
  }

  // Rental list search bar
  const searchInput2 = document.getElementById('searchCustomer2');
  if (searchInput2) {
    searchInput2.addEventListener('input', function () {
      const filter2 = this.value.toLowerCase();
      const rows2 = document.querySelectorAll('#customerTableBody2 tr');
      rows2.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter2) ? '' : 'none';
      });
    });
  }

   // ---------- ADD Staff MODAL ----------
  const openStaffModalBtn = document.getElementById('openAddStaffModal');
  const staffModal = document.getElementById('addStaffModal');      
  const closeStaffModalBtn = document.getElementById('closeAddStaffModal');
  const cancelStaffBtn = document.getElementById('cancelStaffBTN'); 
  if (openStaffModalBtn) {
    openStaffModalBtn.onclick = () => staffModal.style.display = 'flex';
  }
  if (closeStaffModalBtn) {
    closeStaffModalBtn.onclick = () => staffModal.style.display = 'none';
  }
  if (cancelStaffBtn) {
    cancelStaffBtn.onclick = (e) => {
      e.preventDefault();
      staffModal.style.display = 'none';
    };
  }
    // staff search bar
  const searchStaff = document.getElementById('searchStaff');
  if (searchStaff) {
    searchStaff.addEventListener('input', function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#staffTableBody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }
  // ---------- EDIT STAFF ----------
  document.querySelectorAll('.editStaffBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('editStaffID').value = btn.dataset.id;
      document.getElementById('editStaffLastName').value = btn.dataset.lastname;
      document.getElementById('editStaffFirstName').value = btn.dataset.firstname;
      document.getElementById('editMiddleInitial').value = btn.dataset.minit;
      document.getElementById('editAddress').value = btn.dataset.address;
      document.getElementById('editContactNumber').value = btn.dataset.contact;
      document.getElementById('editSalary').value = btn.dataset.salary;

      document.getElementById('editStaffModal').style.display = 'flex';
    });
  });
  const closeEditStaffModal = document.querySelector('.closeEditStaffModal');
  if (closeEditStaffModal) {
    closeEditStaffModal.addEventListener('click', () => {
      document.getElementById('editStaffModal').style.display = 'none';
    });
  }


  // ---------- SELECT CUSTOMER MODAL ----------
  const modalSelect = document.getElementById("selectCustomerModal");
  const openSelectCustomerBtn = document.getElementById("openCustomerModal");
  const closeSelectCustomerBtn = document.getElementById("closeCustomerModal");
  const rentalForm = document.getElementById('rentalFormId'); 
  rentalForm.addEventListener('submit', function (e) {
    const customerID = document.getElementById('selectedCustomerID').value;

    if (!customerID) {
      e.preventDefault();
      alert('Please select a customer before submitting the rental.');
      return;
    }
  });

  if (openSelectCustomerBtn && modalSelect) {
    openSelectCustomerBtn.onclick = () => modalSelect.style.display = "flex";
  }
  if (closeSelectCustomerBtn) {
    closeSelectCustomerBtn.onclick = () => modalSelect.style.display = "none";
  }

  function selectCustomer(id, name, contact) {
    document.getElementById('selectedCustomerID').value = id;
    document.getElementById('selectedCustomerName').value = name;
    document.getElementById('selectedCustomerContact').value = contact;
    modalSelect.style.display = "none";
  }
  window.selectCustomer = selectCustomer; // expose to global

  // select customer search bar
  const searchInput = document.getElementById('searchCustomer');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#customerTableBody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }

  // ---------- EDIT CUSTOMER ----------
  document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('editID').value = btn.dataset.id;
      document.getElementById('editLastName').value = btn.dataset.lname;
      document.getElementById('editFirstName').value = btn.dataset.fname;
      document.getElementById('editMiddleName').value = btn.dataset.mname;
      document.getElementById('editProvince').value = btn.dataset.province;
      document.getElementById('editCity').value = btn.dataset.city;
      document.getElementById('editBarangay').value = btn.dataset.barangay;
      document.getElementById('editDetailed').value = btn.dataset.detailed;
      document.getElementById('editContact').value = btn.dataset.contact;

      document.getElementById('editModal').style.display = 'flex';
    });
  });
  const closeEditModal = document.querySelector('.closeModal');
  if (closeEditModal) {
    closeEditModal.addEventListener('click', () => {
      document.getElementById('editModal').style.display = 'none';
    });
  }



  // rental edit
 document.querySelectorAll('.editRentalBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('editRentalID').value = this.dataset.id;
        
        const customerId = this.dataset.customerid;
        if (!customerId) {
            alert("Error: No customer ID found in this record");
            return;
        }
        document.getElementById('EditselectedCustomerID').value = customerId;
        document.getElementById('EditselectedCustomerName').value = this.dataset.name || '';
        document.getElementById('EditselectedCustomerContact').value = this.dataset.contact || '';
        
        document.getElementById('EditcarType').value = this.dataset.cartype || '';
        document.getElementById('Editrate').value = this.dataset.rate || '';
        document.getElementById('EditnumberOfDays').value = this.dataset.days || '';
        
        const dateStart = this.dataset.datestart ? this.dataset.datestart.split(' ')[0] : '';
        document.getElementById('EditdateStart').value = dateStart;
        
        updateEditCalculations();
        
        document.getElementById('EditRentalsModal').style.display = 'flex';
    });
  });
  

  window.selectEditCustomer = function(id, name, contact) {
    document.getElementById('EditselectedCustomerID').value = id;
    document.getElementById('EditselectedCustomerName').value = name;
    document.getElementById('EditselectedCustomerContact').value = contact;
    document.getElementById('EditselectCustomerModal').style.display = 'none';
  };


  const editCustomerModal = document.getElementById('EditselectCustomerModal');
  const editCloseCustomerBtn = document.getElementById('EditcloseCustomerModal');

  if (editCloseCustomerBtn) {
    editCloseCustomerBtn.addEventListener('click', function() {
        editCustomerModal.style.display = 'none';
    });
  }

  // edit rent info search bar
  const searchInput3 = document.getElementById('EditsearchCustomer');
  if (searchInput3) {
    searchInput3.addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#EditcustomerTableBody3 tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }

  function updateEditCalculations() {
    const rate = parseFloat(document.getElementById('Editrate').value) || 0;
    const days = parseInt(document.getElementById('EditnumberOfDays').value) || 0;
    const startDate = document.getElementById('EditdateStart').value;
    
    const total = rate * days;
    document.getElementById('EditTotalDisplay').value = total.toFixed(2);
    document.getElementById('EditTotal').value = total.toFixed(2);
    
    if (startDate) {
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + days);
        document.getElementById('EditdateEnd').value = endDate.toISOString().split('T')[0];
        document.getElementById('EditdateEnd1').value = endDate.toISOString().split('T')[0];
    }
  }


  document.getElementById('EditRentalFormId').addEventListener('submit', function(e) {
    const customerId = document.getElementById('EditselectedCustomerID').value;
    if (!customerId) {
        e.preventDefault();
        alert("Please select a customer before updating");
        return;
    }

    if (!document.getElementById('EditcarType').value || 
        !document.getElementById('Editrate').value ||
        !document.getElementById('EditnumberOfDays').value ||
        !document.getElementById('EditdateStart').value) {
        e.preventDefault();
        alert("Please fill all required fields");
        return;
    }
  });

  document.getElementById('Editrate').addEventListener('input', updateEditCalculations);
  document.getElementById('EditnumberOfDays').addEventListener('input', updateEditCalculations);
  document.getElementById('EditdateStart').addEventListener('change', updateEditCalculations);

  document.getElementById('EditopenCustomerModal').addEventListener('click', function() {
    document.getElementById('EditselectCustomerModal').style.display = 'flex';
  });

  document.getElementById('closeRentEditInformation').addEventListener('click', function() {
    document.getElementById('EditRentalsModal').style.display = 'none';
  });
  
  document.getElementById('rentcancelRent').addEventListener('click', function() {
    document.getElementById('EditRentalsModal').style.display = 'none';
  });



  // ---------- ADD USER OR ADMIN MODAL ----------
  const openAddUserModalBtn = document.getElementById('openAddUserModal');
  const addUserModal = document.getElementById('addUserModal');
  const closeAddUserModalBtn = document.getElementById('closeAddUserModal');
  const cancelAddUserBtn = document.getElementById('cancelAddUser');

  if (openAddUserModalBtn) {
    openAddUserModalBtn.onclick = () => addUserModal.style.display = 'flex';
  }
  if (closeAddUserModalBtn) {
    closeAddUserModalBtn.onclick = () => addUserModal.style.display = 'none';
  }
  if (cancelAddUserBtn) {
    cancelAddUserBtn.onclick = (e) => {
      e.preventDefault();
      addUserModal.style.display = 'none';
    };
  }

    // search functionality for USER AND ADMIN //
    const searchInput5 = document.getElementById('searchCustomer5');
    if (searchInput5) {
    searchInput5.addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#customerTableBody5 tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }

    // edit functionality for USER AND ADMIN //
  document.querySelectorAll('.editUserBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('editUserID').value = btn.dataset.id;
      document.getElementById('editemail').value = btn.dataset.email;
      document.getElementById('editUsername').value = btn.dataset.username;
      document.getElementById('editPassword').value = btn.dataset.password;
      document.getElementById('editRole').value = btn.dataset.role;

      document.getElementById('editUserModal').style.display = 'flex';
    });
  });
  const closeEditUserModal = document.querySelector('.closeUserModal');
  if (closeEditUserModal) {
    closeEditUserModal.addEventListener('click', () => {
      document.getElementById('editUserModal').style.display = 'none';
    });
  }



});
