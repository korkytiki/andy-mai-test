Invoicing System For a Hardware -Andy Mai Test

SETUP

- git clone
- on application/config/config.php, change $config['base_url'] value to appropriate url based on your local
	- it should be http://localhost/andy-mai-test, but maybe you changed the root folder name to cris-test, then it should be http://localhost/cris-test
- on application/config/database.php, enter the appropriate database configuration based on your local (e.g. username/password)
- import the andy_mai_test.sql file inside the root folder, this should create the database, tables, etc.
- when entering the URL, you should be redirected to the login page
	- you may enter the ff. credentials:
		- username: 
		- password:
		- or
		- username: 
		- password:

NOTES

- most JS files are attached via CDN, make sure you have a proper internet connection
- 4 pages
	- dashboard
	- invoicing
	- products
	- customers (hidden)
	- login

Nice to have

- needs proper session management (JWT)
- validation error messaging using JSON format for REST like api
- separate JS files and php views
