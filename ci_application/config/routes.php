<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$maintenance = FALSE;

if ($maintenance)
{
	// Site down for maintenance
	$route['default_controller'] = "maintenance";
	$route['(:any)']             = "maintenance";
}
else
{
	$route['default_controller'] = "home";

	$route['my-files']                   = "my_files";
	$route['my-files/(:any)']            = "my_files/$1";
	$route['manage-my-files']            = "manage_my_files";
	$route['manage-my-files/(:any)']     = "manage_my_files/$1";
	$route['account/add-funds']          = "account/add_funds";
	$route['upload/public']              = "upload/public_photo";
	$route['upload/private']             = "upload/private_photo";
	$route['contributors/public/(:num)'] = "contributors/assets/$1/1";
	$route['models/public/(:num)']       = "models/assets/$1/1";
	$route['models/photosets/(:num)']    = "models/assets/$1/3";
	$route['models/videos/(:num)']       = "models/assets/$1/5";
	$route['upcoming-features']          = "content/upcoming_features";

	// Regular Expression Route for users list
	$route['management/users/([0-4])\.(all|[01])\.(all|[01])\.(all|[01])/(:any)'] = "management/users/index/$1.$2.$3.$4/$5";

	// Regular Expression Route for assets list
	$route['management/assets/([0-5])\.(all|[01])\.(all|[01])\.(all|[01])/(:any)'] = "management/assets/index/$1.$2.$3.$4/$5";

	// Routes for users/gallery subpages
	$route['management/users/gallery/(:num)/view/(:num)']       = "management/users/gallery_view/$1/$2";
	$route['management/users/gallery/(:num)/add/(:num)']        = "management/users/gallery_add/$1/$2";
	$route['management/users/gallery/(:num)/add/(:num)/(:num)'] = "management/users/gallery_add/$1/$2/$3";
	$route['management/users/gallery/(:num)/delete']            = "management/users/gallery_delete/$1";
	$route['management/users/gallery/(:num)/approve/(:num)']    = "management/users/gallery_approve/$1/$2";
}

$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */