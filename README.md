# Storefront

Description: The Storefront is a PHP site made for administrators to create products so their customers can purchase them. The Credit Card Processing is done through Stripe.

Github Link: https://github.com/jaireddjawed/Storefront

Project Layout:

```

/assets - Public CSS and JS Files
/components - Reusable HTML and PHP Files that are used for many pages
/includes - PHP Files that have resuable code required on many pages, including login and Database connection
/layouts - Layout of the PHP file
/models - SQL models to create tables
/uploads - Directory Where Images are uploaded
/vendor - composer php files
.env - environment variables


```

Concepts Used:
<li>
  MVC
  Models - SQL Table Models Folder
  Views - All Views are in the /pages directory
  Controllers - JS Controllers in assets/js directory, PHP Controllers Are in pages/ directory
</li>

<li>
Passed cookie to PHP from Javascript in assets/js/view-product.js
Handled Cookie in /pages/view-product/product-price.php
</li>

<li>
Writing Images to uploads in pages/create-product/handle-create-product.php
</li>

<li>
Databases SQL)
SQL tables in /models directory
</li>

<li>
Form Validation and Regexes In /assets/js directory
Example File:
/assets/js/handle-signup.js
</li>

<li>
User Admin Login:
Users are signed up in /pages/signup/handle-signup.php
</li>

<li>
User Sessions using the login() function in includes/LoginFunctions.php
Pages are Secured using the isLoggedIn() Function includes/LoginFunctions.php
</li>

<h1>Steps To Run</h1>

<li>Create a .env file at the top directory and add the following</li>

```

PRODUCT_NAME=Base
SERVER_NAME=YOUR_SERVER_NAME
USER_NAME=YOUR_USER_NAME
PASSWORD=YOUR_PASSWORD
DB_NAME=YOUR_DB_NAME

STRIPE_KEY=pk_test_mv9NwCnCEwG5TEysHUXpkM9x
STRIPE_SECRET=sk_test_0d3VhjbF7Ik745gmDcsAvdif

```


<li>Run models/index.php to create and seed the database</li>

<li>Login</li>

Administrator: <br/>

Email-Address: johndoeadmin@localhost.com
Password: HelloWorld1

Basic User: <br/>

Email-Address: johndoebasic@localhost.com
Password: HelloWorld1

<h3>When using cards</h3>

<br/>
Card Number: 5454 5454 5454 5454
<br/>
Exp Month: 11
<br />
Exp Year: 20
<br/>
Postal Code:
12345
