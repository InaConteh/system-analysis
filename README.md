# Grass Root Football Agency Management System

**Theme:** Growing Sierra Leone Talent - Shaping The Future

A dynamic web application for managing a football agency's player directory, contracts, and recruiting. Built with PHP, MySQL, CSS (Vanilla), and JavaScript.

## 🚀 Features

### 🌟 Public Features

- **Dynamic Player Directory**: View all represented players with real-time data.
- **Player Details**: Individual contract pages (`contract.php`) showing specific stats, club info, market value, and **Video Highlights**.
- **User Authentication**: Secure Login and Registration system for fans/scouts.
- **Responsive Design**: Mobile-friendly layout with smooth scroll animations.
- **Downloadable Reports**: Generate and download comprehensive text summaries for any player, including Agent and Market details.

### 🛡 Admin Features (Role-Based Access)

- **Dashboard Access**: Special admin privileges.
- **Player Management**:
  - **Add Player**: Upload photos, set nationality, club, and age.
  - **Edit Player**: Update details, change images, and **Manage Video Highlights** (Upload MP4s or add YouTube links).
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
    - Import the `sql/schema.sql` file provided in this folder.
    - **Crucial Step**: Run the update script to ensure all columns exist:
      - Access: `http://localhost/football_agency/update_schema.php`

3.  **Database Configuration**:

    - Open `includes/db_connect.php`.
    - Verify credentials (default XAMPP is User: `root`, Pass: `[blank]`).

4.  **Admin Account Setup**:
    - Create the "Super Admin" account by visiting:
      - `http://localhost/football_agency/admin_setup.php`
    - **Default Credentials**:
      - Email: `admin@agency.com`
      - Password: `AdminSecret123!`

## 📁 File Structure (Updated)

- **`css/`**: Contains `style.css` (Main stylesheet).
- **`js/`**: Contains `main.js` (Animations and interactions).
- **`includes/`**: Reusable PHP components (`db_connect.php`, `footer.php`).
- **`sql/`**: Database schema files.
- **`images/`**: Player photos and the **Grass Root Logo**.
- `index.php`: Homepage with "Growing Sierra Leone Talent" theme.
- `players.php`: Main directory (Role-protected).
- `contract.php`: Dynamic individual player page.
- `admin_setup.php`: Script to generate admin user.
- `add_player.php` / `edit_player.php`: Admin management pages.
- `uploads/`: Directory where player images and video highlights are stored.

## 🎨 Credits

- **Design**: Custom polished UI with glassmorphism and sticky navigation.
- **Branding**: **Grass Root Football Agency** - Focusing on nurturing local talent.
