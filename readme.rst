Vendr
=====

Vendr is an open-source Point of Sale (POS) and inventory management system
designed for restaurants. It supports order processing, inventory control,
kitchen display, table management, online ordering and reporting.

Badges
------
.. image:: https://img.shields.io/github/stars/UNES97/vendr?style=social
   :target: https://github.com/UNES97/vendr
.. image:: https://img.shields.io/github/license/UNES97/vendr
   :target: https://github.com/UNES97/vendr/blob/main/LICENSE
.. image:: https://img.shields.io/badge/Framework-CodeIgniter%203-red
   :target: https://codeigniter.com/
.. image:: https://img.shields.io/badge/Technology-PHP%20%7C%20MySQL%20%7C%20TailwindCSS-blue
   :target: https://vendr-app.vercel.app/

Quick Summary
-------------
- Framework: CodeIgniter 3 (MVC)
- Backend: PHP 7.2+
- Database: MySQL 5.6+
- Frontend: TailwindCSS
- Additional: Endroid QR Code, Picqer Barcode, TCPDF

Features
--------
- Point of Sale (POS): multi-order types (Dine-in, Takeaway, Delivery), multiple payments, discounts.
- Inventory Control: stock tracking, SKU & barcode generation, low-stock alerts.
- Kitchen Display System (KDS): real-time order updates and status workflow.
- Table Management: QR table ordering, real-time table status, sections.
- Menu & Recipe Management: recipes, ingredient tracking, cost calculations.
- Online Ordering: public order submission, delivery management, minimum order rules.
- Staff & User Management: role-based access (Admin, Manager, Cashier, Chef, Waiter), shifts.
- Reports & Analytics: sales, revenue, payment breakdowns, inventory and monthly comparisons.
- Expense Management: categorized expenses, attachments, date filters.
- QR Code Menu: generate QR codes per table for contactless menus.

Getting Started
---------------

Prerequisites
~~~~~~~~~~~~~
- PHP 7.2+
- MySQL 5.6+
- Apache or Nginx
- Composer
- (Optional for local macOS dev) MAMP

Installation
~~~~~~~~~~~~

1. Clone the repository

   .. code-block:: bash

       git clone https://github.com/UNES97/vendr.git
       cd vendr

2. Create database and import schema

   .. code-block:: bash

       # Adjust credentials as needed (MAMP default MySQL user is usually 'root' with no password)
       mysql -u root -p -e "CREATE DATABASE vendr"
       mysql -u root -p vendr < db/pos_db.sql

3. Configure the application

   .. code-block:: bash

       cp application/config/database.php.example application/config/database.php
       # Edit application/config/database.php with your DB credentials
       # Edit application/config/config.php and set $config['base_url']

4. Install PHP dependencies

   .. code-block:: bash

       composer install

5. Start the server

   - Using MAMP:
     1. Place the vendr folder in your MAMP htdocs directory, e.g. /Applications/MAMP/htdocs/vendr
     2. Start Apache and MySQL via the MAMP app
     3. Visit the configured $config['base_url'] in your browser

   - Quick test with PHP built-in server (development only):

     .. code-block:: bash

         # Run from the project root
         php -S localhost:8000 -t .

Notes
~~~~~
- MAMP default MySQL user is often 'root' with no password. Adjust commands above if you use a different user/password.
- The built-in PHP server is not recommended for production use.
- After first login, change the default admin credentials.

Default Credentials
-------------------
- Email: admin@restaurant.local
- Password: 123456

Security note: Change the default credentials immediately after first login.

Development / Contribution
--------------------------
Contributions are welcome. Please open issues or pull requests on the GitHub repository.
See the CONTRIBUTING guide in the repo for details.

License
-------
Vendr is licensed under the MIT License. See the LICENSE file for details.

Contact
-------
Built by restaurant operators for restaurant operators.
Project: https://github.com/UNES97/vendr