<?php
require_once '../src/auth.php';

logout();

header("Location: login.php");
exit;