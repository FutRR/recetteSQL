<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');


if (isset($_GET["action"])) {

    switch ($_GET["action"]) {
        case "listerRecettes":
            break;

        case "infosRecettes":
            if (isset($_GET["id"])) {
                $index = $_GET["id"];
                echo
                    header("Location:details.php");
            }
            break;
    }
}
