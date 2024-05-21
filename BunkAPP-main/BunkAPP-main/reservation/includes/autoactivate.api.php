<?php

include("dbconnection.php");

function AutoReservate($conn)
{
    $today = date("Y-m-d");
    $query = "UPDATE reservations SET status = 'active' WHERE start_date <= '$today' AND end_date >= '$today'";
    mysqli_query($conn, $query);
}

function AutoNonActivate($conn)
{
    $today = date("Y-m-d");
    $query = "UPDATE reservations SET status = 'inactive' WHERE end_date < '$today' OR start_date > '$today'";
    mysqli_query($conn, $query);
}

AutoReservate($conn);
AutoNonActivate($conn);

?>