# Jewelry Inventory Manager

A single-user jewelry inventory management web application built with the LAMP stack on Ubuntu in Oracle VirtualBox.

## Overview

Jewelry Inventory Manager is a local web application designed to demonstrate full-stack development and systems administration skills. The project includes database-driven inventory management, secure admin access, image handling, search and filtering, CSV export, and HTTPS setup in a lab environment.

## Features

- Admin authentication.
- CRUD operations for jewelry items.
- Image upload support.
- Search and filtering.
- Pagination.
- CSV export.
- HTTPS enabled in a local lab environment.

## Tech Stack

- Ubuntu
- Apache
- PHP
- MariaDB
- HTML / CSS
- VirtualBox

## Project Breakdown

**Environment setup:**
The application was deployed on Ubuntu 24.04.3 in Oracle VirtualBox with allocated resources for local testing and development. 
Apache, PHP, and MariaDB were installed and configured to form a working LAMP environment

**Database integration:**
MariaDB was used as the database layer for compatibility with MySQL workflows. 
The application connects to the database through PHP and retrieves joined data from related tables such as items and categories.

**Application features:**
The project includes create, read, update, and delete functionality for jewelry items, along with inventory search and filtering. 
It also supports CSV export, admin authentication, and image uploads for item records.

**Security and deployment:**
The setup includes basic hardening steps such as configuring HTTPS with a self-signed certificate for local secure access. 
Admin-only actions are protected behind authentication.

## Screenshots

<table>
  <tr>
    <td align="center"><strong>DB data test</strong></td>
    <td align="center"><strong>Inventory test list</strong></td>
  </tr>
  <tr>
    <td><img src="./screenshots/DB-SampleData.png" alt="DB data test" width="400"></td>
    <td><img src="./screenshots/List-DBdata.png" alt="Inventory test list" width="400"></td>
  </tr>

  <tr>
    <td align="center"><strong>Admin login</strong></td>
    <td align="center"><strong>Add Item</strong></td>
  </tr>
  <tr>
    <td><img src="./screenshots/Login-formUI.png" alt="Admin login" width="400"></td>
    <td><img src="./screenshots/Add-overview.png" alt="Add Item" width="400"></td>
  </tr>

  <tr>
    <td align="center"><strong>Edit Item</strong></td>
    <td align="center"><strong>Inventory List</strong></td>
  </tr>
  <tr>
    <td><img src="./screenshots/Edit-overview.png" alt="Edit Item" width="400"></td>
    <td><img src="./screenshots/Inventory-list.png" alt="Inventory List" width="400"></td>
  </tr>
</table>

## Setup

1. Provision an Ubuntu 24.04 virtual machine in Oracle VirtualBox.
2. Install and validate Apache, PHP, and MariaDB.
3. Secure the MariaDB installation and create the project database.
4. Import the schema and sample inventory data.
5. Verify PHP-to-MariaDB connectivity.
6. Implement and test the application workflow, including login, CRUD operations, filtering, CSV export, and image upload.
7. Configure HTTPS locally with a self-signed certificate.

## Security Notes

Sensitive values such as passwords, credentials, and local-only configuration details are not included in this repository.  
Use your own local configuration when testing the project.

## What I Learned

This project reinforced my experience in:
- Linux server setup and local application deployment.
- Apache and PHP configuration in a LAMP environment.
- MariaDB database design, connectivity, and data handling.
- Troubleshooting web, database, and file-related issues during development.
- Implementing operational features such as authentication, logging, search, filtering, and exports.
- Applying security-minded practices in a local lab environment.
- Structuring a full-stack project in a way that demonstrates production-style thinking.

## Status

Completed and documented as a portfolio project.
