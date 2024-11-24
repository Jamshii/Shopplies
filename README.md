# SHOPPLIES

Welcome to the Shopplies project! This is the repository for our PHP-based e-commerce website. Please follow the instructions below to download and set up the project on your local machine.

## Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) on your machine.
- Install [Git](https://git-scm.com/) if you haven’t already.


## 1. Clone the Repository
To get the files on your device, you need to clone this repository. Follow these steps:

Using GitHub Desktop (if you prefer not to use the command line):
1. Open GitHub Desktop.
2. Go to File > Clone Repository.
3. Choose Clone from URL and paste the URL of this repository.
4. Select the local directory where you want to save the project.
5. Click Clone.

## 2. Set Up XAMPP
Since this project uses PHP and MySQL, you need to have XAMPP installed to run the local server.

1. Download and install XAMPP from Apache Friends.
2. Start Apache and MySQL from the XAMPP control panel.
3. Place the project files inside the htdocs directory located in the XAMPP installation folder. For example:
`C:\xampp\htdocs\Shopplies`
4. Open your browser and go to http://localhost/Shopplies to view the project.

## 3. Database Setup
The project requires a MySQL database. Follow these steps to set it up:

Where to find?
The database dump file is located in the `db/` folder of this repository: `shopplies_sample.sql`.

Create Database
1. Open **phpMyAdmin** (go to `http://localhost/phpmyadmin`).
2. Create a new database (e.g., `shopplies_db`).

Import Database Structure:
1. In phpMyAdmin, select the database you just created.
2. Click on the **Import** tab.
3. Click **Choose File**, and select the `shopplies_db.sql` file from the `db/` folder of the repository.
4. Click **Go** to import the database structure and data into your local database.

Configure Database Connection:
1. Edit the db.php file in the project and provide your database credentials:

## 4. Running the Project Locally
Once you’ve completed the setup:

1. Start XAMPP (if not already running).
2. Open your browser and go to http://localhost/Shopplies.
3. You should see the home page of the Shopplies e-commerce website.

## 5. Pushing Changes
After making changes to the code, be sure to push your updates back to the repository:

1. Add your changes to the staging area:
git add .

2. Commit your changes:
git commit -m "Description of changes"

3. Push the changes to GitHub:
git push origin main
