<?php
// Create connection
$mysqli = new mysqli("localhost","root","password","hsr");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$agency_1 = "DROP TABLE IF EXISTS agency; ";
$agency_2 = "CREATE TABLE agency (
				agency_id CHAR(3),
				agency_name VARCHAR(30),
				agency_url VARCHAR(40),
				agency_timezone VARCHAR(20),
				agency_lang CHAR(2),
				agency_phone CHAR(12),
				agency_fare_url CHAR(1)); ";
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

$routes_1 = 	"DROP TABLE IF EXISTS routes; ";
$routes_2 = 	"CREATE TABLE routes (
					route_id SMALLINT(4),
					agency_id CHAR(3),
					route_short_name TINYINT(2),
					route_long_name VARCHAR(20),
					route_desc CHAR(1),
					route_type CHAR(1),
					route_url CHAR(1),
					route_color CHAR(6),
					route_text_color CHAR(6) ); ";
$routes_3 = 	"LOAD DATA LOCAL INFILE './google_transit/routes.txt'
							INTO TABLE routes
							FIELDS TERMINATED BY ','
							LINES TERMINATED BY '\r\n'
							IGNORE 1 LINES; ";

if (!$mysqli->query($routes_1) || 
	!$mysqli->query($routes_2) ||
	!$mysqli->query($routes_3)) {
    echo "Error in routes table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created routes table.\r\n";
}

$shapes_1 = 	"DROP TABLE IF EXISTS shapes; ";
$shapes_2 = 	"CREATE TABLE shapes (
					shape_id SMALLINT(5),
					shape_pt_lat NUMERIC(8,6),
					shape_pt_lon NUMERIC(8,6),
					shape_pt_sequence SMALLINT(4),
					shape_dist_traveled NUMERIC(6,4) ); ";
$shapes_3 = 	"LOAD DATA LOCAL INFILE './google_transit/shapes.txt'
					INTO TABLE shapes
					FIELDS TERMINATED BY ','
					LINES TERMINATED BY '\r\n'
					IGNORE 1 LINES; ";

if (!$mysqli->query($shapes_1) || 
	!$mysqli->query($shapes_2) ||
	!$mysqli->query($shapes_3)) {
    echo "Error in shapes table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created shapes table.\r\n";
}

$stop_times_1 = 	"DROP TABLE IF EXISTS stop_times; ";
$stop_times_2 = 	"CREATE TABLE stop_times (
						trip_id MEDIUMINT(6),
						arrival_time TIME,
						departure_time TIME,
						stop_id MEDIUMINT(6),
						stop_sequence SMALLINT(3),
						stop_headsign CHAR(1),
						pickup_type TINYINT(1),
						drop_off_type TINYINT(1),
						shape_dist_traveled NUMERIC(6,4) ); ";
$stop_times_3 = 	"LOAD DATA LOCAL INFILE './google_transit/stop_times.txt'
					INTO TABLE stop_times
					FIELDS TERMINATED BY ','
					LINES TERMINATED BY '\r\n'
					IGNORE 1 LINES; ";

if (!$mysqli->query($stop_times_1) || 
	!$mysqli->query($stop_times_2) ||
	!$mysqli->query($stop_times_3)) {
    echo "Error in stop_times table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created stop_times table.\r\n";
}

$stops_1 = 	"DROP TABLE IF EXISTS stops; ";
$stops_2 = 	"CREATE TABLE stops (
				stop_id MEDIUMINT(6),
				stop_code SMALLINT(4),
				stop_name VARCHAR(60),
				stop_desc VARCHAR(60),
				stop_lat NUMERIC(8,6),
				stop_lon NUMERIC(8,6),
				zone_id CHAR(1),
				stop_url CHAR(1),
				location_type CHAR(1),
				parent_station CHAR(1),
				wheelchair_boarding CHAR(1) ); ";
$stops_3 = 	"LOAD DATA LOCAL INFILE './google_transit/stops.txt'
				INTO TABLE stops
				FIELDS TERMINATED BY ','
				LINES TERMINATED BY '\r\n'
				IGNORE 1 LINES; ";

if (!$mysqli->query($stops_1) || 
	!$mysqli->query($stops_2) ||
	!$mysqli->query($stops_3)) {
    echo "Error in stops table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created stops table.\r\n";
}

$trips_1 = 	"DROP TABLE IF EXISTS trips; ";
$trips_2 = 	"CREATE TABLE trips (
				route_id SMALLINT(4),
				service_id TINYINT(1),
				trip_id MEDIUMINT(6),
				trip_headsign VARCHAR(30),
				trip_short_name CHAR(1),
				direction_id TINYINT(1),
				block_id MEDIUMINT(6),
				shape_id SMALLINT(5) ); ";
$trips_3 = 	"LOAD DATA LOCAL INFILE './google_transit/trips.txt'
				INTO TABLE trips
				FIELDS TERMINATED BY ','
				LINES TERMINATED BY '\r\n'
				IGNORE 1 LINES; ";

if (!$mysqli->query($trips_1) || 
	!$mysqli->query($trips_2) ||
	!$mysqli->query($trips_3)) {
    echo "Error in trips table: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "Created trips table.\r\n";
}

?>