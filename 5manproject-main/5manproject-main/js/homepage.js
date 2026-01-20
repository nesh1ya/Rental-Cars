const dashboard = document.getElementById('dashboardSection');
const customer = document.getElementById('recordCustomer');
const staff = document.getElementById('recordStaff');
const rentals = document.getElementById('salesSection');
const report = document.getElementById('reportSection');
const user = document.getElementById('userSection');
const settings = document.getElementById('settingsSection');
const navItems = document.querySelectorAll('.navItem');
const actionsTD = document.querySelectorAll('.actionsTD');

// pang role base
if(userRole == "Staff"){
  document.getElementById('users_button').style.display = 'none';
  document.getElementById('staff_button').style.display = 'none';
  actionsTD.forEach(el => {
    el.style.display = 'none';
  });
}

// para sa active dashboard
navItems.forEach(item => {
  item.addEventListener('click', () => {

    navItems.forEach(i => i.classList.remove('active'));

    item.classList.add('active');
  });
});

function hideAll() {
  dashboard.style.display = 'none';
  customer.style.display = 'none';
  staff.style.display = 'none';
  rentals.style.display = 'none';
  report.style.display = 'none';
  user.style.display = 'none';
  settings.style.display = 'none';
}

document.getElementById('dashboard_button').addEventListener('click', () => {
  hideAll();
  dashboard.style.display = 'block';
});

document.getElementById('customers_button').addEventListener('click', () => {
  hideAll();
  customer.style.display = 'block';
});

document.getElementById('staff_button').addEventListener('click', () => {
  hideAll();
  staff.style.display = 'block';
});

document.getElementById('rentals_button').addEventListener('click', () => {
  hideAll();
  rentals.style.display = 'block';
});

document.getElementById('reports_button').addEventListener('click', () => {
  hideAll();
  report.style.display = 'block';
});

document.getElementById('users_button').addEventListener('click', () => {
  hideAll();
  user.style.display = 'block';
});

// PARA NAKA DEFAULT YUNG STYLE NG DASHBOARD
dashboard.style.display = 'block';

// CALCULATIONS
const rateInput = document.getElementById('rate');
const daysInput = document.getElementById('numberOfDays');
const totalDisplay = document.getElementById('totalDisplay');
const totalHidden = document.getElementById('total');

const dateStartInput = document.getElementById('dateStart');
const dateEndDisplay = document.getElementById('dateEnd');
const dateEndHidden = document.getElementById('dateEnd1');

function updateTotalAndEndDate() {
  const rate = parseFloat(rateInput.value);
  const days = parseInt(daysInput.value);
  const startDate = new Date(dateStartInput.value);


  if (!isNaN(rate) && !isNaN(days)) {
    const total = rate * days;
    totalDisplay.value = total.toFixed(2);
    totalHidden.value = total.toFixed(2);
  } else {
    totalDisplay.value = '';
    totalHidden.value = '';
  }


  if (!isNaN(days) && dateStartInput.value) {
    const endDate = new Date(startDate);
    endDate.setDate(startDate.getDate() + days);


    const options = { year: 'numeric', month: 'long', day: '2-digit' };
    const formattedEnd = endDate.toLocaleDateString('en-US', options).replace(',', '').replace(' ', '-');

    dateEndDisplay.type = "text"; 
    dateEndDisplay.value = formattedEnd;

    const isoString = endDate.toISOString().split('T')[0];
    dateEndHidden.value = isoString;
  } else {
    dateEndDisplay.value = '';
    dateEndHidden.value = '';
  }
}

rateInput.addEventListener('input', updateTotalAndEndDate);
daysInput.addEventListener('input', updateTotalAndEndDate);
dateStartInput.addEventListener('input', updateTotalAndEndDate);

// daily sales
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('dateFilterForm');
    const rentalsCount = document.getElementById('rentals-count');
    const salesAmount = document.getElementById('sales-amount');
    
    // Function to load data
    function loadData(date) {
        rentalsCount.textContent = 'Loading...';
        salesAmount.textContent = 'Loading...';
        
        fetch(`get_daily_data.php?dateSelection=${date}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    rentalsCount.textContent = data.rentals;
                    salesAmount.textContent = `₱${data.sales.toLocaleString()}`;
                } else {
                    throw new Error(data.message || 'Unknown server error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                rentalsCount.textContent = 'Error';
                salesAmount.textContent = 'Error';
            });
    }
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const date = document.getElementById('dateSelection').value;
        loadData(date);
    });
    
    // Load today's data on page load
    const today = new Date().toLocaleDateString('en-CA');
    document.getElementById('dateSelection').value = today;
    loadData(today);
});

document.getElementById('settings_button').addEventListener('click', () => {
  hideAll();
  settings.style.display = 'block';
});

// for confirming password
document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
  const newPassword = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  if (newPassword !== confirmPassword) {
    e.preventDefault();
    document.getElementById('changePasswordMessage').textContent = "New passwords do not match.";
    document.getElementById('changePasswordMessage').style.color = "red";
  }
});