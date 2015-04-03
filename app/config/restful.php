<?php

return array(
	'name' => 'Restful API',
	'mailgun' => false,
	'emails' => array(
		'info' => 'info@example.com',
		'admin' => 'admin@example.com',
		'noreply' => 'noreply@example.com',
		'support' => 'support@example.com',
		'career' => 'career@example.com',
	),
	'api' => array(
		'keys' => array(
			'hru8ud28emvr394jd'
		)
	),
	'paths' => array(
		'profile_img' => public_path() . "/img/profile"
	),
	'urls' => array(
		'profile_img' => asset('img/profile')
	),
	'defaults' => array(
		'pagination' => array(
			'limit' => 20
		),
		'user' => array(
			'status' => 'active'
		),
		'role' => array(
			'name' => 'user'
		),
		'group' => array(
			'name' => 'user'
		)
	)
);
