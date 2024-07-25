<?php
// functions/connection/process_logout.php
session_start();
session_unset();
session_destroy();
header("Location: /e_comerce_2/frontend/frontend_projet/");
exit;
