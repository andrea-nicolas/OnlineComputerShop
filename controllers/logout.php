<?php

require_once __DIR__ . "/bootstrap.php";

session_unset();
session_destroy();

setcookie(REMEMBER_COOKIE, "", time() - 3600, "/");

redirect(link_to("/views/login.php?msg=out"));
