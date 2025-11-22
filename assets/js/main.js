// Main JavaScript file for Car Rental System

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Email validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    return strength;
}

// Calculate total days and price
function calculateBookingPrice(startDate, endDate, pricePerDay) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) return { days: 1, price: pricePerDay };
    
    return {
        days: diffDays,
        price: diffDays * pricePerDay
    };
}

// Date validation for booking
function validateBookingDates() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    if (!startDate || !endDate) return true;
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    
    if (start < today) {
        alert('Start date cannot be in the past');
        startDate.value = '';
        return false;
    }
    
    if (end < start) {
        alert('End date must be after start date');
        endDate.value = '';
        return false;
    }
    
    return true;
}

// Update total price dynamically
function updateBookingPrice() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const pricePerDayElement = document.getElementById('price_per_day');
    const totalDaysElement = document.getElementById('total_days');
    const totalPriceElement = document.getElementById('total_price');
    
    if (!startDate || !endDate || !pricePerDayElement) return;
    
    if (startDate.value && endDate.value) {
        const pricePerDay = parseFloat(pricePerDayElement.value);
        const booking = calculateBookingPrice(startDate.value, endDate.value, pricePerDay);
        
        if (totalDaysElement) totalDaysElement.value = booking.days;
        if (totalPriceElement) totalPriceElement.value = booking.price.toFixed(2);
        
        // Update display
        const displayElement = document.getElementById('price_display');
        if (displayElement) {
            displayElement.innerHTML = `
                <strong>Booking Summary:</strong><br>
                Duration: ${booking.days} day(s)<br>
                Total Price: $${booking.price.toFixed(2)}
            `;
        }
    }
}

// Confirm delete action
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Search functionality
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (!input || !table) return;
    
    const filter = input.value.toUpperCase();
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        let display = false;
        const cells = rows[i].getElementsByTagName('td');
        
        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                const textValue = cell.textContent || cell.innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    display = true;
                    break;
                }
            }
        }
        
        rows[i].style.display = display ? '' : 'none';
    }
}

// Filter cars by criteria
function filterCars() {
    const brand = document.getElementById('filter_brand')?.value.toUpperCase();
    const transmission = document.getElementById('filter_transmission')?.value;
    const fuelType = document.getElementById('filter_fuel')?.value;
    
    const cards = document.querySelectorAll('.car-card');
    
    cards.forEach(card => {
        const cardBrand = card.getAttribute('data-brand')?.toUpperCase();
        const cardTransmission = card.getAttribute('data-transmission');
        const cardFuel = card.getAttribute('data-fuel');
        
        let display = true;
        
        if (brand && brand !== 'ALL' && cardBrand !== brand) display = false;
        if (transmission && transmission !== 'all' && cardTransmission !== transmission) display = false;
        if (fuelType && fuelType !== 'all' && cardFuel !== fuelType) display = false;
        
        card.style.display = display ? '' : 'none';
    });
}

// Print invoice
function printInvoice() {
    window.print();
}

// Image preview for file upload
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('image_preview');
            const container = document.getElementById('imagePreviewContainer');
            if (preview && container) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Show loading spinner
function showLoading() {
    const loader = document.getElementById('loading');
    if (loader) loader.style.display = 'flex';
}

// Hide loading spinner
function hideLoading() {
    const loader = document.getElementById('loading');
    if (loader) loader.style.display = 'none';
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Set min date for date inputs to today
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        if (input.id === 'start_date' || input.id === 'end_date') {
            input.setAttribute('min', today);
        }
    });
    
    // Add event listeners for booking date changes
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    if (startDate) {
        startDate.addEventListener('change', function() {
            validateBookingDates();
            updateBookingPrice();
        });
    }
    
    if (endDate) {
        endDate.addEventListener('change', function() {
            validateBookingDates();
            updateBookingPrice();
        });
    }
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
