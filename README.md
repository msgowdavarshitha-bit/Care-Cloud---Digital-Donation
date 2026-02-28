# CareCloud – Digital Charity Management System

## Overview
CareCloud is a comprehensive digital charity management system built with Node.js and MongoDB. It enables users to make monetary and item donations, track their contributions, and view the impact of their charitable activities. The platform connects donors with communities in need through a secure and user-friendly interface.

## Features
- **User Registration & Authentication**: Secure sign-up and login system
- **Multi-Currency Support**: Accept donations in various currencies (USD, EUR, GBP, INR, and 35+ more)
- **Multiple Payment Methods**: Stripe, PayPal, Razorpay, Bank Transfer, UPI
- **Donation Tracking**: View all donations in a searchable, sortable table
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Security**: Password hashing with bcrypt, session-based authentication
- **Modern UI**: Beautiful gradient backgrounds, animations, and visual elements

## Tech Stack
- **Backend**: Node.js with Express.js
- **Database**: MongoDB with Mongoose ODM
- **Template Engine**: EJS (Embedded JavaScript)
- **Styling**: CSS with modern gradients and animations
- **Icons**: Font Awesome
- **Authentication**: Session-based with bcrypt password hashing

## Payment System

### Supported Currencies
The system supports 40+ currencies including:
- USD (US Dollar)
- EUR (Euro)
- GBP (British Pound)
- INR (Indian Rupee)
- JPY (Japanese Yen)
- CAD (Canadian Dollar)
- AUD (Australian Dollar)
- CHF (Swiss Franc)
- CNY (Chinese Yuan)
- And many more...

### Supported Payment Methods
1. **Stripe**: Credit/debit card processing
2. **PayPal**: Popular online payment platform
3. **Razorpay**: Leading payment gateway in India
4. **Bank Transfer**: Direct bank transfers
5. **UPI**: Unified Payments Interface (popular in India)

### Security Features
- **PCI DSS Compliance**: Adheres to Payment Card Industry Data Security Standards
- **SSL Encryption**: All transactions are protected with 256-bit SSL encryption
- **Tokenization**: Payment information is tokenized to prevent data exposure
- **Zero Data Retention**: We do not store payment details on our servers
- **Fraud Prevention**: Built-in fraud detection mechanisms

## Installation & Setup

### Prerequisites
- Node.js (v14 or higher)
- MongoDB installed and running

### Installation Steps
1. Clone the repository
```bash
git clone <repository-url>
cd carecloud-charity-management
```

2. Install dependencies
```bash
npm install
```

3. Create `.env` file with the following content:
```env
PORT=3000
MONGODB_URI=mongodb://localhost:27017/donation_db
SESSION_SECRET=your-super-secret-session-key-here
STRIPE_API_KEY=your_stripe_secret_key
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret
RAZORPAY_KEY_ID=your_razorpay_key_id
RAZORPAY_KEY_SECRET=your_razorpay_key_secret
NODE_ENV=development
```

4. Start the application
```bash
npm start
```

5. Access the application at `http://localhost:3000`

## Usage Guide

### User Registration
1. Navigate to `/signup`
2. Fill in personal details (first name, last name, email, password, etc.)
3. Validate email format and password strength (minimum 6 characters)
4. Submit form to create account
5. Receive confirmation message
6. Navigate to `/login` to access account

### Making Donations
1. **For New Users**:
   - Register via `/signup`
   - Log in via `/login`

2. **For Existing Users**:
   - Log in via `/login`

3. **Make Donation**:
   - Navigate to `/donate`
   - Select donation type (money or item)
   - Fill in donation details:
     - Name (pre-filled for logged-in users)
     - Email (pre-filled for logged-in users)
     - Mobile
     - Amount (for monetary donations)
     - Currency selection (40+ supported)
     - Item type (Books, Clothes, Food, Stationery, Electronics, Furniture)
     - Item description
     - Quantity
     - Delivery option (Drop-off or Request Pick-up)
     - Address (if requesting pick-up)
     - Payment method (Stripe, PayPal, Razorpay, Bank Transfer, UPI)
   - Submit donation
   - Receive confirmation on success

### Viewing Donations
1. Navigate to `/items-donated`
2. View all donations in a table format
3. See donation details including:
   - Donor name
   - Email
   - Amount (if monetary)
   - Currency
   - Item type
   - Description
   - Quantity
   - Date of donation
   - Payment status

### Submitting Suggestions
1. Navigate to `/suggest`
2. Fill in name, email, and suggestion
3. Submit for review
4. Receive confirmation message

## Database Schema

### Users Collection
```javascript
{
  _id: ObjectId,
  firstName: String,
  lastName: String,
  email: String (unique),
  password: String (hashed),
  mobile: String,
  gender: String (enum: male, female, other),
  address: String,
  createdAt: Date
}
```

### Donations Collection
```javascript
{
  _id: ObjectId,
  userId: ObjectId (reference to user),
  name: String,
  email: String,
  mobile: String,
  amount: Number,
  currency: String (default: 'USD'),
  itemType: String,
  description: String,
  quantity: Number,
  deliveryOption: String,
  address: String,
  paymentMethod: String,
  transactionId: String,
  paymentStatus: String (default: 'pending'),
  createdAt: Date
}
```

### Payment Transactions Collection
```javascript
{
  _id: ObjectId,
  userId: ObjectId (reference to user),
  donationId: ObjectId (reference to donation),
  amount: Number,
  currency: String,
  paymentMethod: String,
  transactionId: String,
  status: String (enum: 'pending', 'completed', 'failed', 'refunded'),
  provider: String,
  providerTransactionId: String,
  paymentDetails: Object,
  createdAt: Date
}
```

### Suggestions Collection
```javascript
{
  _id: ObjectId,
  name: String,
  email: String,
  suggestion: String,
  createdAt: Date
}
```

## API Endpoints

### Authentication
- `GET /signup` - Show signup form
- `POST /signup` - Register new user
- `GET /login` - Show login form
- `POST /login` - Authenticate user
- `GET /logout` - Log out user

### Donations
- `GET /donate` - Show donation form
- `POST /donate` - Submit donation
- `GET /items-donated` - View all donations
- `GET /payment/process/:donationId` - Process payment for donation
- `POST /payment/process/:donationId` - Process payment submission
- `GET /payment-success/:transactionId` - Show payment success page

### Other
- `GET /suggest` - Show suggestion form
- `POST /suggest` - Submit suggestion
- `GET /about` - About Us page
- `GET /faq` - FAQ page
- `GET /success-story` - Success Stories page
- `GET /thank-you` - Thank You page

## Security Best Practices

### Payment Security
- All payment information is transmitted over HTTPS
- Credit card information is never stored on our servers
- Tokenization is used for sensitive payment data
- Regular security audits and penetration testing

### Data Protection
- Passwords are hashed using bcrypt with salt rounds
- Session management with secure cookies
- Input validation and sanitization
- Protection against SQL injection and XSS attacks

## Contributing
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Support
For support, please contact us at info@carecloud.org or create an issue in the GitHub repository.

---

Built with ❤️ for a better world