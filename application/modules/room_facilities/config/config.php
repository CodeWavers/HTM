<?php
// module directory name
$HmvcConfig['Room_serviceModel']["_title"]     = "room_facilities All Method";
$HmvcConfig['Room_serviceModel']["_description"] = "room_facilities method like Service";


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['Room_serviceModel']['_database'] = true;
$HmvcConfig['Room_serviceModel']["_tables"] = array(
	'roomfacilitytype',
	'roomfacilitydetails',
	'roomsizemesurement',
	'roomfaility_ref_accomodation'
);
