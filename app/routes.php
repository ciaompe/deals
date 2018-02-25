<?php

/*
Client Routes
*/
require APP_PATH.'/app/routes/client/home.php';
require APP_PATH.'/app/routes/client/auth/auth.php';
require APP_PATH.'/app/routes/client/auth/account.php';
require APP_PATH.'/app/routes/client/deals.php';
require APP_PATH.'/app/routes/client/categories.php';
require APP_PATH.'/app/routes/client/search.php';

/*
Advertise
 */
require APP_PATH.'/app/routes/client/advertise.php';


/*
Admin Routes
*/
require APP_PATH.'/app/routes/admin/home.php';
require APP_PATH.'/app/routes/admin/account.php';
require APP_PATH.'/app/routes/admin/users.php';
require APP_PATH.'/app/routes/admin/deals.php';
require APP_PATH.'/app/routes/admin/categories.php';
require APP_PATH.'/app/routes/admin/advertise.php';


/*
Errors Routes
 */
require APP_PATH.'/app/routes/errors/errors.php';