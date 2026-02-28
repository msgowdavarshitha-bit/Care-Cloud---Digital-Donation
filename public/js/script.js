// JavaScript for CareCloud Charity Management System

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if(hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
    
    // Ripple effect for buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple element
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            
            // Position the ripple
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            // Add ripple to button
            this.appendChild(ripple);
            
            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add loading state to submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            if(submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds if no response
                setTimeout(() => {
                    if(submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = submitBtn.dataset.originalText || '<i class="fas fa-donate"></i> Submit Donation';
                    }
                }, 3000);
            }
        });
    });
    
    // Payment form specific functionality
    const paymentMethodSelect = document.getElementById('paymentMethod');
    if(paymentMethodSelect) {
        // Store original text for submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        if(submitBtn) {
            submitBtn.dataset.originalText = submitBtn.innerHTML;
        }
    }
});

// Utility functions for currency formatting
function formatCurrency(amount, currency) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 2
    }).format(amount);
}

// Function to convert between currencies (for demo purposes)
function convertCurrency(amount, fromCurrency, toCurrency, rates) {
    if(fromCurrency === toCurrency) return amount;
    
    // Convert to USD first, then to target currency
    const usdAmount = amount / rates[fromCurrency];
    return usdAmount * rates[toCurrency];
}

// Function to validate payment card numbers (simplified)
function validateCardNumber(cardNumber) {
    // Remove spaces and dashes
    const cleaned = cardNumber.replace(/[\s-]/g, '');
    
    // Check if it's all digits and has valid length
    if(!/^\d{13,19}$/.test(cleaned)) {
        return false;
    }
    
    // Simple Luhn algorithm check
    return luhnCheck(cleaned);
}

function luhnCheck(cardNumber) {
    let sum = 0;
    let isEven = false;
    
    for(let i = cardNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(cardNumber.charAt(i));
        
        if(isEven) {
            digit *= 2;
            if(digit > 9) {
                digit -= 9;
            }
        }
        
        sum += digit;
        isEven = !isEven;
    }
    
    return sum % 10 === 0;
}

// Function to validate expiry date
function validateExpiryDate(expiryDate) {
    const [month, year] = expiryDate.split('/');
    if(!month || !year) return false;
    
    const expMonth = parseInt(month);
    const expYear = parseInt('20' + year);
    
    if(isNaN(expMonth) || isNaN(expYear)) return false;
    if(expMonth < 1 || expMonth > 12) return false;
    
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth() + 1;
    
    if(expYear < currentYear) return false;
    if(expYear === currentYear && expMonth < currentMonth) return false;
    
    return true;
}

// Function to validate CVV
function validateCVV(cvv, cardNumber) {
    const cleaned = cvv.replace(/\s/g, '');
    const firstDigit = cardNumber.replace(/\s/g, '').charAt(0);
    
    // American Express has 4-digit CVV
    if(firstDigit === '3') {
        return /^\d{4}$/.test(cleaned);
    }
    
    // Other cards have 3-digit CVV
    return /^\d{3}$/.test(cleaned);
}

// Function to show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="close-notification">&times;</button>
    `;
    
    // Add to top of body
    document.body.prepend(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
    
    // Add close functionality
    const closeBtn = notification.querySelector('.close-notification');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
}