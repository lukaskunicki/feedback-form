# Feedback Block Plugin

A WordPress plugin providing two Gutenberg blocks for handling feedback submissions:

## Features

### Submission Form Block
- Form with required fields (First Name, Last Name, Email, Subject, Message)
- Auto-fill for logged-in users
- AJAX submission
- Client-side validation

### Submission List Block (Admin Only)
- List view of all submissions
- Expandable details
- Pagination
- Secure access control

## Requirements
- WordPress 6.1+
- PHP 7.4+
- Composer
- Node.js & npm

## Installation

1. Clone the repository
2. Install dependencies:
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

## Development

```bash
# Install all dependencies
composer install
npm install

# Development build with watch
npm run start

# Production build
npm run build

# Format code
npm run format        # Format all files (JS, CSS, PHP)
npm run format:js    # Format only JavaScript files
npm run format:css   # Format only CSS/SCSS files

# Lint code
npm run lint         # Lint all files
npm run lint:js     # Lint only JavaScript files
npm run lint:css    # Lint only CSS/SCSS files
```
