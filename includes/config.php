<?php
ob_start();
//config.phpが最初に読み込まれるのでここでセッションスタート
session_start();

$timezone = date_default_timezone_set("Europe/London");

$con = mysqli_connect("localhost", "root", "root", "slotify");

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}
