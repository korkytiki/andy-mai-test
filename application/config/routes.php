<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;
$route['dashboard'] = 'dashboard/index';

$route['customer']['get'] = 'customer/get_all';
$route['customer/(:num)']['get'] = 'customer/get/$1';
$route['customer']['post'] = 'customer/post';
$route['customer/(:num)']['put'] = 'customer/put/$1';
$route['customer/(:num)']['delete'] = 'customer/remove/$1';

$route['product']['get'] = 'product/get_all';
$route['product/(:num)']['get'] = 'product/get/$1';
$route['product']['post'] = 'product/post';
$route['product/(:num)']['put'] = 'product/put/$1';
$route['product/(:num)']['delete'] = 'product/remove/$1';

$route['invoice']['get'] = 'invoice/get_all';
$route['invoice/(:num)']['get'] = 'invoice/get/$1';
$route['invoice']['post'] = 'invoice/post';
$route['invoice/(:num)']['put'] = 'invoice/put/$1';
$route['invoice/(:num)']['delete'] = 'invoice/remove/$1';

$route['invoice_item']['get'] = 'invoice_item/get_all';
$route['invoice_item/(:num)']['get'] = 'invoice_item/get/$1';
$route['invoice_item']['post'] = 'invoice_item/post';
$route['invoice_item/(:num)']['put'] = 'invoice_item/put/$1';
$route['invoice_item/(:num)']['delete'] = 'invoice_item/remove/$1';
$route['invoice_item/batch_save'] = 'invoice_item/batch_save';

$route['default_controller'] = 'dashboard';
