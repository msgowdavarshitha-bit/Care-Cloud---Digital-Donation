const express = require('express');
const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');
const session = require('express-session');
const bodyParser = require('body-parser');
const ejs = require('ejs');
const expressEjsLayouts = require('express-ejs-layouts');
const path = require('path');
const crypto = require('crypto');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;
console.log("test change");

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(expressEjsLayouts);
app.set('layout', './layout');
app.use(session({
    secret: process.env.SESSION_SECRET || 'your-secret-key',
    resave: false,
    saveUninitialized: false
}));

// MongoDB Connection
mongoose.connect(process.env.MONGODB_URI || 'mongodb://localhost:27017/donation_db', {
    useNewUrlParser: true,
    useUnifiedTopology: true
});

// User Schema
const userSchema = new mongoose.Schema({
    firstName: { type: String, required: true },
    lastName: { type: String },
    email: { type: String, required: true, unique: true },
    password: { type: String, required: true },
    mobile: { type: String },
    gender: { type: String, enum: ['male', 'female', 'other'] },
    address: { type: String },
    createdAt: { type: Date, default: Date.now }
});

// Donation Schema
const donationSchema = new mongoose.Schema({
    userId: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
    name: { type: String, required: true },
    email: { type: String, required: true },
    mobile: { type: String },
    amount: { type: Number },
    currency: { type: String, default: 'USD' }, // Added currency field
    itemType: { type: String, required: true },
    description: { type: String, required: true },
    quantity: { type: Number, required: true },
    deliveryOption: { type: String, required: true },
    address: { type: String },
    paymentMethod: { type: String }, // Added payment method field
    transactionId: { type: String }, // Added transaction ID field
    paymentStatus: { type: String, default: 'pending' }, // Added payment status
    createdAt: { type: Date, default: Date.now }
});

// Payment Transaction Schema
const paymentTransactionSchema = new mongoose.Schema({
    userId: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
    donationId: { type: mongoose.Schema.Types.ObjectId, ref: 'Donation' },
    amount: { type: Number, required: true },
    currency: { type: String, required: true },
    paymentMethod: { type: String, required: true },
    transactionId: { type: String, required: true },
    status: { type: String, enum: ['pending', 'completed', 'failed', 'refunded'], default: 'pending' },
    provider: { type: String }, // Payment provider (stripe, paypal, etc.)
    providerTransactionId: { type: String }, // Provider's transaction ID
    paymentDetails: { type: Object }, // Additional payment details
    createdAt: { type: Date, default: Date.now }
});

// Suggestion Schema
const suggestionSchema = new mongoose.Schema({
    name: { type: String, required: true },
    email: { type: String, required: true },
    suggestion: { type: String, required: true },
    createdAt: { type: Date, default: Date.now }
});

// Models
const User = mongoose.model('User', userSchema);
const Donation = mongoose.model('Donation', donationSchema);
const PaymentTransaction = mongoose.model('PaymentTransaction', paymentTransactionSchema);
const Suggestion = mongoose.model('Suggestion', suggestionSchema);

// Import payment processor
const PaymentProcessor = require('./payment-integration-example');
const paymentProcessor = new PaymentProcessor();

// Helper function to check if user is authenticated
const isAuthenticated = (req, res, next) => {
    if (req.session.userId) {
        next();
    } else {
        res.redirect('/login');
    }
};

// Currency conversion rates (for demo purposes)
const CURRENCY_RATES = {
    'USD': 1,
    'EUR': 0.85,
    'GBP': 0.73,
    'INR': 83, // Indian Rupee
    'JPY': 149, // Japanese Yen
    'CAD': 1.36, // Canadian Dollar
    'AUD': 1.52, // Australian Dollar
    'CHF': 0.91, // Swiss Franc
    'CNY': 7.24, // Chinese Yuan
    'SGD': 1.36, // Singapore Dollar
    'NZD': 1.66, // New Zealand Dollar
    'SEK': 10.78, // Swedish Krona
    'NOK': 10.88, // Norwegian Krone
    'DKK': 6.86, // Danish Krone
    'KRW': 1332.89, // South Korean Won
    'RUB': 96.54, // Russian Ruble
    'BRL': 5.67, // Brazilian Real
    'MXN': 18.24, // Mexican Peso
    'ZAR': 18.85, // South African Rand
    'AED': 3.67, // UAE Dirham
    'SAR': 3.75, // Saudi Riyal
    'KWD': 0.30, // Kuwaiti Dinar
    'TRY': 27.85, // Turkish Lira
    'THB': 36.25, // Thai Baht
    'MYR': 4.70, // Malaysian Ringgit
    'IDR': 15642.00, // Indonesian Rupiah
    'PKR': 278.00, // Pakistani Rupee
    'BDT': 109.00, // Bangladeshi Taka
    'LKR': 303.00, // Sri Lankan Rupee
    'NPR': 133.00, // Nepalese Rupee
    'BHD': 0.38, // Bahraini Dinar
    'QAR': 3.64, // Qatari Rial
    'OMR': 0.38, // Omani Rial
    'ILS': 3.75, // Israeli Shekel
    'ARS': 366.00, // Argentine Peso
    'CLP': 915.00, // Chilean Peso
    'COP': 4785.00, // Colombian Peso
    'EGP': 47.00, // Egyptian Pound
    'GHS': 14.50, // Ghanaian Cedi
    'JMD': 157.00, // Jamaican Dollar
    'JOD': 0.71, // Jordanian Dinar
    'KES': 140.00, // Kenyan Shilling
    'MAD': 10.80, // Moroccan Dirham
    'NGN': 1505.00, // Nigerian Naira
    'TND': 3.30, // Tunisian Dinar
    'UAH': 39.00, // Ukrainian Hryvnia
    'VND': 25475.00 // Vietnamese Dong
};

// Routes
// Home Route
app.get('/', async (req, res) => {
    try {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        res.render('index', { user, title: 'CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('index', { user: null, title: 'CareCloud – Digital Charity Management System' });
    }
});

// Signup Route
app.get('/signup', (req, res) => {
    res.render('signup', { error: null, success: null, title: 'Sign Up - CareCloud – Digital Charity Management System' });
});

app.post('/signup', async (req, res) => {
    const { firstName, lastName, email, password, confirmPassword, mobile, gender, address } = req.body;

    // Validation
    if (!firstName || !email || !password) {
        return res.render('signup', { error: 'All required fields must be filled.', success: null, title: 'Sign Up - CareCloud – Digital Charity Management System' });
    }

    if (password !== confirmPassword) {
        return res.render('signup', { error: 'Passwords do not match.', success: null, title: 'Sign Up - CareCloud – Digital Charity Management System' });
    }

    if (password.length < 6) {
        return res.render('signup', { error: 'Password must be at least 6 characters long.', success: null, title: 'Sign Up - CareCloud – Digital Charity Management System' });
    }

    try {
        // Check if user already exists
        const existingUser = await User.findOne({ email });
        if (existingUser) {
            return res.render('signup', { error: 'Email already exists.', success: null, title: 'Sign Up - CareCloud – Digital Charity Management System' });
        }

        // Hash password and create user
        const hashedPassword = await bcrypt.hash(password, 12);
        const newUser = new User({
            firstName,
            lastName,
            email,
            password: hashedPassword,
            mobile,
            gender,
            address
        });

        await newUser.save();
        res.render('signup', { error: null, success: 'Registration successful! You can now log in.', title: 'Sign Up - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('signup', { error: 'Error occurred during registration.', success: null, title: 'Sign Up - CareCloud – Digital Charity Management System' });
    }
});

// Login Route
app.get('/login', (req, res) => {
    res.render('login', { error: null, title: 'Login - CareCloud – Digital Charity Management System' });
});

app.post('/login', async (req, res) => {
    const { email, password } = req.body;

    if (!email || !password) {
        return res.render('login', { error: 'Please enter both email and password.', title: 'Login - CareCloud – Digital Charity Management System' });
    }

    try {
        const user = await User.findOne({ email });
        if (!user || !await bcrypt.compare(password, user.password)) {
            return res.render('login', { error: 'Invalid email or password.', title: 'Login - CareCloud – Digital Charity Management System' });
        }

        req.session.userId = user._id;
        req.session.userName = `${user.firstName} ${user.lastName}`;
        req.session.userEmail = user.email;
        
        res.redirect('/');
    } catch (err) {
        console.error(err);
        res.render('login', { error: 'Error occurred during login.', title: 'Login - CareCloud – Digital Charity Management System' });
    }
});

// Logout Route
app.get('/logout', (req, res) => {
    req.session.destroy((err) => {
        if (err) {
            console.error(err);
        }
        res.redirect('/');
    });
});

// Donate Route - Updated to support payment processing
app.get('/donate', isAuthenticated, async (req, res) => {
    try {
        const user = await User.findById(req.session.userId);
        // Pass available currencies to the view
        const currencies = Object.keys(CURRENCY_RATES);
        res.render('donate', { user, error: null, success: null, title: 'Donate - CareCloud – Digital Charity Management System', currencies });
    } catch (err) {
        console.error(err);
        res.redirect('/login');
    }
});

app.post('/donate', isAuthenticated, async (req, res) => {
    const { name, email, mobile, amount, currency, itemType, description, quantity, deliveryOption, address, paymentMethod } = req.body;

    // Validation
    if (!name || !email || !itemType || !description || !quantity) {
        const user = await User.findById(req.session.userId);
        const currencies = Object.keys(CURRENCY_RATES);
        return res.render('donate', { 
            user, 
            error: 'All required fields must be filled.', 
            success: null,
            title: 'Donate - CareCloud – Digital Charity Management System',
            currencies
        });
    }

    if (amount && amount <= 0) {
        const user = await User.findById(req.session.userId);
        const currencies = Object.keys(CURRENCY_RATES);
        return res.render('donate', { 
            user, 
            error: 'Amount must be greater than zero.', 
            success: null,
            title: 'Donate - CareCloud – Digital Charity Management System',
            currencies
        });
    }

    if (quantity <= 0) {
        const user = await User.findById(req.session.userId);
        const currencies = Object.keys(CURRENCY_RATES);
        return res.render('donate', { 
            user, 
            error: 'Quantity must be greater than zero.', 
            success: null,
            title: 'Donate - CareCloud – Digital Charity Management System',
            currencies
        });
    }

    if (deliveryOption === 'Request Pick-up' && !address) {
        const user = await User.findById(req.session.userId);
        const currencies = Object.keys(CURRENCY_RATES);
        return res.render('donate', { 
            user, 
            error: 'Address is required for pick-up delivery option.', 
            success: null,
            title: 'Donate - CareCloud – Digital Charity Management System',
            currencies
        });
    }

    try {
        // Create donation record
        const donation = new Donation({
            userId: req.session.userId,
            name,
            email,
            mobile,
            amount: amount ? parseFloat(amount) : null,
            currency: currency || 'USD', // Default to USD if not specified
            itemType,
            description,
            quantity: parseInt(quantity),
            deliveryOption,
            address,
            paymentMethod: paymentMethod || 'offline' // Default to offline for non-monetary donations
        });

        await donation.save();

        // If it's a monetary donation, process payment
        if (amount && amount > 0 && paymentMethod && paymentMethod !== 'offline') {
            // Prepare payment data
            const paymentData = {
                amount: parseFloat(amount),
                currency: currency || 'USD',
                donorName: name,
                donorEmail: email,
                donationId: donation._id
            };

            // Process payment based on selected method
            let paymentResult;
            switch (paymentMethod) {
                case 'stripe':
                    paymentResult = await paymentProcessor.processStripePayment(paymentData);
                    break;
                case 'paypal':
                    paymentResult = await paymentProcessor.processPayPalPayment(paymentData);
                    break;
                case 'razorpay':
                    paymentResult = await paymentProcessor.processRazorpayPayment(paymentData);
                    break;
                case 'bank-transfer':
                    paymentResult = await paymentProcessor.processBankTransferPayment(paymentData);
                    break;
                case 'upi':
                    paymentResult = await paymentProcessor.processUpiPayment(paymentData);
                    break;
                default:
                    return res.render('donate', { 
                        user: await User.findById(req.session.userId), 
                        error: 'Invalid payment method selected.', 
                        success: null,
                        title: 'Donate - CareCloud – Digital Charity Management System',
                        currencies: Object.keys(CURRENCY_RATES)
                    });
            }

            if (paymentResult.success) {
                // Create payment transaction record
                const paymentTransaction = new PaymentTransaction({
                    userId: req.session.userId,
                    donationId: donation._id,
                    amount: parseFloat(amount),
                    currency: currency || 'USD',
                    paymentMethod,
                    transactionId: paymentResult.transactionId,
                    status: paymentResult.status,
                    provider: paymentResult.provider,
                    providerTransactionId: paymentResult.transactionId,
                    paymentDetails: paymentResult
                });

                await paymentTransaction.save();
                
                // Update donation status
                donation.paymentStatus = paymentResult.status;
                donation.transactionId = paymentResult.transactionId;
                await donation.save();
                
                res.render('payment-success', { 
                    donation, 
                    paymentTransaction, 
                    user: await User.findById(req.session.userId), 
                    title: 'Payment Successful - CareCloud – Digital Charity Management System'
                });
            } else {
                // Handle payment failure
                donation.paymentStatus = 'failed';
                await donation.save();
                
                res.render('donate', { 
                    user: await User.findById(req.session.userId), 
                    error: `Payment failed: ${paymentResult.error || 'Unknown error occurred'}`, 
                    success: null,
                    title: 'Donate - CareCloud – Digital Charity Management System',
                    currencies: Object.keys(CURRENCY_RATES)
                });
            }
        } else {
            // For non-monetary donations or offline payments
            res.render('donate', { 
                user: await User.findById(req.session.userId), 
                error: null, 
                success: 'Donation submitted successfully!',
                title: 'Donate - CareCloud – Digital Charity Management System',
                currencies: Object.keys(CURRENCY_RATES)
            });
        }
    } catch (err) {
        console.error(err);
        const user = await User.findById(req.session.userId);
        const currencies = Object.keys(CURRENCY_RATES);
        res.render('donate', { 
            user, 
            error: 'Error occurred during donation submission.', 
            success: null,
            title: 'Donate - CareCloud – Digital Charity Management System',
            currencies
        });
    }
});

// Payment Processing Routes
app.get('/payment/process/:donationId', isAuthenticated, async (req, res) => {
    try {
        const donation = await Donation.findById(req.params.donationId);
        if (!donation || donation.userId.toString() !== req.session.userId.toString()) {
            return res.status(403).send('Unauthorized');
        }
        
        res.render('payment-form', {
            donation,
            currencies: Object.keys(CURRENCY_RATES),
            title: 'Process Payment - CareCloud – Digital Charity Management System'
        });
    } catch (err) {
        console.error(err);
        res.redirect('/donate');
    }
});

app.post('/payment/process/:donationId', isAuthenticated, async (req, res) => {
    try {
        const { amount, currency, paymentMethod } = req.body;
        const donation = await Donation.findById(req.params.donationId);
        
        if (!donation || donation.userId.toString() !== req.session.userId.toString()) {
            return res.status(403).send('Unauthorized');
        }
        
        // Update donation with payment details
        donation.amount = parseFloat(amount);
        donation.currency = currency;
        donation.paymentMethod = paymentMethod;
        
        // Prepare payment data
        const paymentData = {
            amount: parseFloat(amount),
            currency: currency,
            donorName: req.body.donorName,
            donorEmail: req.body.donorEmail,
            donationId: donation._id
        };

        // Process payment based on selected method
        let paymentResult;
        switch (paymentMethod) {
            case 'stripe':
                paymentResult = await paymentProcessor.processStripePayment(paymentData);
                break;
            case 'paypal':
                paymentResult = await paymentProcessor.processPayPalPayment(paymentData);
                break;
            case 'razorpay':
                paymentResult = await paymentProcessor.processRazorpayPayment(paymentData);
                break;
            case 'bank-transfer':
                paymentResult = await paymentProcessor.processBankTransferPayment(paymentData);
                break;
            case 'upi':
                paymentResult = await paymentProcessor.processUpiPayment(paymentData);
                break;
            default:
                return res.redirect(`/payment/process/${req.params.donationId}`);
        }

        if (paymentResult.success) {
            // Create payment transaction record
            const paymentTransaction = new PaymentTransaction({
                userId: req.session.userId,
                donationId: donation._id,
                amount: parseFloat(amount),
                currency: currency,
                paymentMethod,
                transactionId: paymentResult.transactionId,
                status: paymentResult.status,
                provider: paymentResult.provider,
                providerTransactionId: paymentResult.transactionId,
                paymentDetails: paymentResult
            });

            await paymentTransaction.save();
            
            // Update donation status
            donation.paymentStatus = paymentResult.status;
            donation.transactionId = paymentResult.transactionId;
            await donation.save();
            
            res.render('payment-success', { 
                donation, 
                paymentTransaction, 
                user: await User.findById(req.session.userId), 
                title: 'Payment Successful - CareCloud – Digital Charity Management System'
            });
        } else {
            // Handle payment failure
            donation.paymentStatus = 'failed';
            await donation.save();
            
            res.render('payment-form', {
                donation,
                currencies: Object.keys(CURRENCY_RATES),
                error: `Payment failed: ${paymentResult.error || 'Unknown error occurred'}`,
                title: 'Process Payment - CareCloud – Digital Charity Management System'
            });
        }
    } catch (err) {
        console.error(err);
        res.redirect(`/payment/process/${req.params.donationId}`);
    }
});

// Items Donated Route
app.get('/items-donated', async (req, res) => {
    try {
        const donations = await Donation.find().sort({ createdAt: -1 });
        res.render('items-donated', { donations, title: 'Items Donated - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('items-donated', { donations: [], error: 'Error fetching donations.', title: 'Items Donated - CareCloud – Digital Charity Management System' });
    }
});

// Suggest Route
app.get('/suggest', async (req, res) => {
    try {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        const userName = user ? `${user.firstName} ${user.lastName}` : '';
        const userEmail = user ? user.email : '';
        
        res.render('suggest', { user, userName, userEmail, error: null, success: null, title: 'Suggest - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('suggest', { user: null, userName: '', userEmail: '', error: 'Error loading page.', success: null, title: 'Suggest - CareCloud – Digital Charity Management System' });
    }
});

app.post('/suggest', async (req, res) => {
    const { name, email, suggestion } = req.body;

    // Validation
    if (!name || !email || !suggestion) {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        const userName = user ? `${user.firstName} ${user.lastName}` : '';
        const userEmail = user ? user.email : '';
        
        return res.render('suggest', { 
            user, 
            userName, 
            userEmail, 
            error: 'All fields are required.', 
            success: null,
            title: 'Suggest - CareCloud – Digital Charity Management System'
        });
    }

    if (!/^\S+@\S+\.\S+$/.test(email)) {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        const userName = user ? `${user.firstName} ${user.lastName}` : '';
        const userEmail = user ? user.email : '';
        
        return res.render('suggest', { 
            user, 
            userName, 
            userEmail, 
            error: 'Invalid email format.', 
            success: null,
            title: 'Suggest - CareCloud – Digital Charity Management System'
        });
    }

    try {
        const newSuggestion = new Suggestion({
            name,
            email,
            suggestion
        });

        await newSuggestion.save();
        
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        const userName = user ? `${user.firstName} ${user.lastName}` : '';
        const userEmail = user ? user.email : '';
        
        res.render('suggest', { 
            user, 
            userName, 
            userEmail, 
            error: null, 
            success: 'Thank you for your suggestion! We appreciate your feedback.',
            title: 'Suggest - CareCloud – Digital Charity Management System'
        });
    } catch (err) {
        console.error(err);
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        const userName = user ? `${user.firstName} ${user.lastName}` : '';
        const userEmail = user ? user.email : '';
        
        res.render('suggest', { 
            user, 
            userName, 
            userEmail, 
            error: 'Error occurred during suggestion submission.', 
            success: null,
            title: 'Suggest - CareCloud – Digital Charity Management System'
        });
    }
});

// About Route
app.get('/about', async (req, res) => {
    try {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        res.render('about', { user, title: 'About Us - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('about', { user: null, title: 'About Us - CareCloud – Digital Charity Management System' });
    }
});

// FAQ Route
app.get('/faq', async (req, res) => {
    try {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        res.render('faq', { user, title: 'FAQ - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('faq', { user: null, title: 'FAQ - CareCloud – Digital Charity Management System' });
    }
});

// Success Story Route
app.get('/success-story', async (req, res) => {
    try {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        res.render('success-story', { user, title: 'Success Stories - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('success-story', { user: null, title: 'Success Stories - CareCloud – Digital Charity Management System' });
    }
});

// Thank You Route
app.get('/thank-you', async (req, res) => {
    try {
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        res.render('thank-you', { user, title: 'Thank You - CareCloud – Digital Charity Management System' });
    } catch (err) {
        console.error(err);
        res.render('thank-you', { user: null, title: 'Thank You - CareCloud – Digital Charity Management System' });
    }
});

// Payment Success Route
app.get('/payment-success/:transactionId', async (req, res) => {
    try {
        const transaction = await PaymentTransaction.findOne({ transactionId: req.params.transactionId }).populate('donationId');
        if (!transaction) {
            return res.status(404).render('error', { message: 'Transaction not found', title: 'Error - CareCloud – Digital Charity Management System' });
        }
        
        const user = req.session.userId ? await User.findById(req.session.userId) : null;
        res.render('payment-success', { 
            paymentTransaction: transaction, 
            donation: transaction.donationId,
            user,
            title: 'Payment Successful - CareCloud – Digital Charity Management System' 
        });
    } catch (err) {
        console.error(err);
        res.redirect('/');
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});