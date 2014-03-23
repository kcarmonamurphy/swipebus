<?php
// Create connection
$mysqli = new mysqli("localhost","root","password","hsr");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$agency_1 = "DROP TABLE IF EXISTS agency; ";
$agency_2 = "CREATE TABLE agency (
				agency_id VARCHAR(6),
				agency_name VARCHAR(30),
				agency_url TEXT,
				agency_timezone VARCHAR(20),
				agency_lang VARCHAR(6),
				agency_phone CHAR(12),
				agency_fare_url TEXT); ";
$agency_3 =	"LOAD DATA LOCAL INFILE './google_transit/agency.txt'
				INTO TABLE agency
				FIELDS TERMINATED BY ','
				LINES TERMINATED BY '\r\n'
				IGNORE 1 LINES; ";

if (!$mysqli->query($agency_1) || 
	!$mysqli->query($agency_2) ||
	!$mysqli->query($agency_3)) {
    echo "Error in agency table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created agency table.\r\n";
}

$calendar_1 = 	"DROP TABLE IF EXISTS calendar; ";
$calendar_2 = 	"CREATE TABLE calendar (
					service_id CHAR(1),
					monday CHAR(1),
					tuesday CHAR(1),
					wednesday CHAR(1),
					thursday CHAR(1),
					friday CHAR(1),
					saturday CHAR(1),
					sunday CHAR(1),
					start_date DATE,
					end_date DATE); ";
$calendar_3 = 	"LOAD DATA LOCAL INFILE './google_transit/calendar.txt'
					INTO TABLE calendar
					FIELDS TERMINATED BY ','
					LINES TERMINATED BY '\r\n'
					IGNORE 1 LINES
					(service_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday, @start_date, @end_date) 
					SET start_date = STR_TO_DATE(@start_date, '%Y%m%d'), end_date = STR_TO_DATE(@end_date, '%Y%m%d'); ";

if (!$mysqli->query($calendar_1) || 
	!$mysqli->query($calendar_2) ||
	!$mysqli->query($calendar_3)) {
    echo "Error in calendar table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created calendar table.\r\n";
}

$calendar_dates_1 = 	"DROP TABLE IF EXISTS calendar_dates; ";
$calendar_dates_2 = 	"CREATE TABLE calendar_dates (
							service_id CHAR(1),
							c_date DATE,
							exception_type CHAR(1) ); ";
$calendar_dates_3 =		"LOAD DATA LOCAL INFILE './google_transit/calendar_dates.txt'
							INTO TABLE calendar_dates
							FIELDS TERMINATED BY ','
							LINES TERMINATED BY '\r\n'
							IGNORE 1 LINES
							(service_id, @c_date, exception_type)
							SET c_date = STR_TO_DATE(@c_date, '%Y%m%d'); ";

if (!$mysqli->query($calendar_dates_1) || 
	!$mysqli->query($calendar_dates_2) ||
	!$mysqli->query($calendar_dates_3)) {
    echo "Error in calendar_dates table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created calendar_dates table.\r\n";
}

$fare_attributes_1 = 	"DROP TABLE IF EXISTS fare_attributes; ";
$fare_attributes_2 = 	"CREATE TABLE fare_attributes (
							fare_id VARCHAR(10),
							price NUMERIC(3,2),
							currency_type CHAR(3),
							payment_method CHAR(1),
							transfers CHAR(1),
							transfer_duration CHAR(1) ); ";
$fare_attributes_3 = 	"LOAD DATA LOCAL INFILE './google_transit/fare_attributes.txt'
							INTO TABLE fare_attributes
							FIELDS TERMINATED BY ','
							LINES TERMINATED BY '\r\n'
							IGNORE 1 LINES; ";

if (!$mysqli->query($fare_attributes_1) || 
	!$mysqli->query($fare_attributes_2) ||
	!$mysqli->query($fare_attributes_3)) {
    echo "Error in fare_attributes table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created fare_attributes table.\r\n";
}

?>