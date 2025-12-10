# Grass Root Football Agency Management System

A dynamic web application for managing a football agency's player directory, contracts, and recruiting. Built with PHP, MySQL, CSS (Vanilla), and JavaScript.

## 🚀 Features

### 🌟 Public Features

- **Dynamic Player Directory**: View all represented players with real-time data.
- **Player Details**: Individual contract pages (`contract.php`) showing specific stats, club info, and highlights.
- **User Authentication**: Secure Login and Registration system for fans/scouts.
- **Responsive Design**: Mobile-friendly layout with smooth scroll animations.
- **Downloadable Reports**: Generate and download text summaries for any player.

### 🛡 Admin Features (Role-Based Access)

- **Dashboard Access**: Special admin privileges.
- **Player Management**:
  - **Add Player**: Upload photos, set nationality, club, and age.
  - **Edit Player**: Update details and change images instantly.
  - **Delete Player**: Remove records from the database.
- **Image Uploads**: Real file handling for player profile pictures.

## 🛠 Prerequisites

- **Web Server**: Apache (via XAMPP, WAMP, or MAMP).
- **Database**: MySQL.
- **PHP**: Version 7.4 or higher.

## ⚙ Installation & Setup

1.  **Clone/Place Files**:

    - Ensure the project folder is in your server's root (e.g., `C:\xampp\htdocs\football_agency`).

2.  **Database Setup**:

    - Open **phpMyAdmin**.
    - Create a database named `football_agency_db`.
    - Import the `schema.sql` file provided in this folder.
    - **Crucial Step**: Run the update script to ensure all columns exist:
      - Access: `http://localhost/football_agency/update_schema.php`

3.  **Database Configuration**:

    - Open `db_connect.php`.
    - Verify credentials (default XAMPP is User: `root`, Pass: `[blank]`).

4.  **Admin Account Setup**:
    - Create the "Super Admin" account by visiting:
      - `http://localhost/football_agency/admin_setup.php`
    - **Default Credentials**:
      - Email: `admin@agency.com`
      - Password: `AdminSecret123!`

## 📖 Usage Guide

### Logging In

- Navigate to `login.php`.
- Enter your credentials.
- Admins will see extra controls (Add/Edit/Delete) on the `players.php` page.

### Managing Players (Admin Only)

- **Add**: Click "Add New Player" on the top of the Players Directory. Fill in the form and upload an image.
- **Edit**: Click the "Edit" button on any player card. modify details or upload a new photo. Changes reflect immediately.

### Downloading Reports

- Go to any player's contract page.
- Click the **⬇ Download Report** icon in the header.
- A `.txt` file with the player's profile will be downloaded.

## 📁 File Structure

- `index.php`: Homepage with animations.
- `players.php`: Main directory (Role-protected).
- `contract.php`: Dynamic individual player page.
- `admin_setup.php`: Script to generate admin user.
- `update_schema.php`: Script to update DB table structure.
- `uploads/`: Directory where player images are stored.
- `style.css`: Main stylesheet.
- `main.js`: Scroll animations and interactions.

## 🎨 Credits

- **Design**: Custom polished UI with glassmorphism and sticky navigation.
- **Animations**: CSS3 & Intersection Observer API.
