
let provinces = [];
let cities = [];
let barangays = [];

fetch("../data/provinces.json")
    .then(response => response.json())
    .then(data => {
        provinces = data;
        populateSelect("province", provinces);
    });

fetch("../data/cities.json")
    .then(response => response.json())
    .then(data => {
        cities = data;
    });

fetch("../data/barangays.json")
    .then(response => response.json())
    .then(data => {
        barangays = data;
    });

function populateSelect(selectId, data, level = "province") {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Select</option>';

    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.name; 
        option.textContent = item.name;

        
        if (level === "province") {
            option.setAttribute('data-code', item.prov_code);
        } else if (level === "city") {
            option.setAttribute('data-code', item.mun_code);
            option.setAttribute('data-prov-code', item.prov_code); 
        }
        select.appendChild(option);
    });
}

document.getElementById("province").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const provCode = selectedOption.getAttribute('data-code');

    const filteredCities = cities.filter(city => city.prov_code === provCode);
    populateCitySelect(filteredCities);

    // Reset barangays
    document.getElementById("barangay").innerHTML = '<option value="">Select Barangay</option>';
});

function populateCitySelect(filteredCities) {
    const citySelect = document.getElementById("city");
    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
    filteredCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city.name;
        option.textContent = city.name;
        option.setAttribute('data-code', city.mun_code);
        citySelect.appendChild(option);
    });
}

document.getElementById("city").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const munCode = selectedOption.getAttribute('data-code');

    const filteredBarangays = barangays.filter(barangay => barangay.mun_code === munCode);
    const barangaySelect = document.getElementById("barangay");
    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
    filteredBarangays.forEach(barangay => {
        barangaySelect.innerHTML += `<option value="${barangay.name}">${barangay.name}</option>`;
    });
});