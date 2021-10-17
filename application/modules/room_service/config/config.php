<?php
// module directory name
$HmvcConfig['Room_service_model']["_title"]     = "room_facilities All Method";
$HmvcConfig['Room_service_model']["_description"] = "room_facilities method like Service";


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['Room_service_model']['_database'] = true;
$HmvcConfig['Room_service_model']["_tables"] = array(
	'roomfacilitytype',
	'roomfacilitydetails',
	'roomsizemesurement',
	'roomfaility_ref_accomodation'
);
