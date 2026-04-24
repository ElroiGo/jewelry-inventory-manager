# Jewelry Inventory Manager

A single-user jewelry inventory management web application built with the LAMP stack (Linux, Apache, MariaDB/, PHP) on Ubuntu.

## Overview

Jewelry Inventory Manager is a single-user web application built with the LAMP stack on Ubuntu (Oracle VirtualBox). 
It demonstrates practical full-stack development and systems administration skills through CRUD operations, search and filtering, CSV export, image uploads, admin authentication, and HTTPS configuration.

## Features

- Admin authentication
- Create, read, update, and delete (CRUD) jewelry items
- Image upload support
- Search and filtering
- Pagination
- CSV export
- HTTPS enabled in a local lab environment

## Tech Stack

- Ubuntu
- Apache
- PHP
- MariaDB
- HTML / CSS
- VirtualBox

## Project Breakdown

Environment setup:
The application was deployed on Ubuntu 24.04.3 in Oracle VirtualBox with allocated resources for local testing and development. 
Apache, PHP, and MariaDB were installed and configured to form a working LAMP environment

Database integration:
MariaDB was used as the database layer for compatibility with MySQL workflows. 
The application connects to the database through PHP and retrieves joined data from related tables such as items and categories.

Application features:
The project includes create, read, update, and delete functionality for jewelry items, along with inventory search and filtering. 
It also supports CSV export, admin authentication, and image uploads for item records.

Security and deployment:
The setup includes basic hardening steps such as configuring HTTPS with a self-signed certificate for local secure access. 
Admin-only actions are protected behind authentication.

## Screenshots

Add screenshots here from:
- Inventory list:
  <img width="615" height="402" alt="image" src="https://github.com/user-attachments/assets/17419da1-4c69-4023-a2ad-bebcb5ce58b0" />
- Add item form
- Edit item page
- Search / filter results
- CSV export result
- HTTPS working page

## Setup

1. Install Apache, PHP, and MariaDB on Ubuntu.
2. Clone or download the project files into the web root directory.
3. Create the database and import the SQL schema.
4. Update the database configuration file with your local environment values.
5. Start Apache and MariaDB services.
6. Open the application in your browser.

## Security Notes

Sensitive values such as passwords, credentials, and local-only configuration details are not included in this repository.  
Use your own local configuration when testing the project.

## What I Learned

This project helped strengthen my skills in:
- LAMP stack deployment
- PHP and MariaDB integration
- CRUD application design
- File uploads and form handling
- Local HTTPS configuration
- Technical troubleshooting and debugging

## Status

Completed and documented as a portfolio project.
