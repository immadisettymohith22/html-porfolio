<?php
include 'connect.php';

$query1 = "INSERT INTO events (event_name, category, event_date, description, venue, event_time, event_end_time, image, seats, status) 
VALUES (
    'CodeStorm Hackathon', 
    'Technical Events', 
    '2026-04-30', 
    'CodeStorm Hackathon is an intense and innovative coding competition designed to bring together passionate developers, designers, and problem-solvers. Participants collaborate in teams to build creative and impactful solutions within a limited time frame.', 
    'mt hall (crc block)', 
    '4pm', 
    '8pm', 
    'hackathon.jpg', 
    '150', 
    'Open'
)";

if(!mysqli_query($conn, $query1)) {
    echo "Error inserting event 1: " . mysqli_error($conn) . "\n";
} else {
    echo "Event 1 inserted.\n";
}

$query2 = "INSERT INTO events (event_name, category, event_date, description, venue, event_time, event_end_time, image, seats, status) 
VALUES (
    'Green Campus Drive', 
    'Social & Awareness Events', 
    '2026-04-01', 
    'Tree plantation activities across campus\r\nCleanliness and waste segregation drives\r\nAwareness campaigns on sustainability\r\nStudent volunteer participation', 
    'common graden', 
    '8am', 
    '1pm', 
    'green.jpg', 
    '200', 
    'Open'
)";

if(!mysqli_query($conn, $query2)) {
    echo "Error inserting event 2: " . mysqli_error($conn) . "\n";
} else {
    echo "Event 2 inserted.\n";
}

?>
