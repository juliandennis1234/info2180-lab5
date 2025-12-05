window.onload = function () {
    const lookupBtn = document.getElementById("lookup");
    const lookupCitiesBtn = document.getElementById("lookupCities");
    const countryInput = document.getElementById("country");
    const resultDiv = document.getElementById("result");

    // Lookup countries
    lookupBtn.addEventListener("click", function () {
        const country = countryInput.value.trim();
        fetch(`world.php?country=${encodeURIComponent(country)}`)
            .then(response => response.text())
            .then(data => { resultDiv.innerHTML = data; })
            .catch(error => { resultDiv.innerHTML = "Error fetching data."; console.error(error); });
    });

    // Lookup cities
    lookupCitiesBtn.addEventListener("click", function () {
        const country = countryInput.value.trim();
        fetch(`world.php?country=${encodeURIComponent(country)}&lookup=cities`)
            .then(response => response.text())
            .then(data => { resultDiv.innerHTML = data; })
            .catch(error => { resultDiv.innerHTML = "Error fetching data."; console.error(error); });
    });
};
