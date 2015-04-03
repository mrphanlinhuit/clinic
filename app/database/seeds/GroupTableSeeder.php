<?php

class GroupTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users_group')->delete();

		$group = Group::create(array(
			'id' => 1,
			'status' => 'active',
			'name' => 'admin',
			'description' => 'Full access all user/group.'
		));
		$group = Group::create(array(
			'id' => 2,
			'status' => 'active',
			'name' => 'manager',
			'description' => 'Full access all user.'
		));
		$group = Group::create(array(
			'id' => 3,
			'status' => 'active',
			'name' => 'user',
			'description' => 'User.'
		));


		$referer = Referer::create(array(
			'id' => 1,
			'status' => true,
			'name' => 'Google'
		));
		$referer = Referer::create(array(
			'id' => 2,
			'status' => true,
			'name' => 'Bing',
		));


		$pathology = Pathology::create(array(
			'id' => 1,
			'name' => 'Pathology A'
		));
		$pathology = Pathology::create(array(
			'id' => 2,
			'name' => 'Pathology B',
		));


		$room = Room::create(array(
			'id' => 1,
			'name' => 'Room 101',
			'status' => true,
			'description' => 'description'
		));
		$room = Room::create(array(
			'id' => 2,
			'name' => 'Room 102',
			'status' => true,
			'description' => 'description'
		));

		$treatment = Treatment::create(array(
			'id' => 1,
			'name' => 'First visit',
			'active' => true,
			'duration' => 1,
			'price' => 0,
			'tax' => 0,
			'role' => "doctor"
		));

		$treatment = Treatment::create(array(
			'id' => 2,
			'name' => 'Revesion',
			'active' => true,
			'duration' => 1,
			'price' => 0,
			'tax' => 0,
			'role' => "doctor"
		));

		$treatment = Treatment::create(array(
			'id' => 3,
			'name' => 'Treatment A1 (doctor)',
			'active' => true,
			'duration' => 7,
			'price' => 2,
			'tax' => 3,
			'role' => "doctor"
		));

		$treatment = Treatment::create(array(
			'id' => 4,
			'name' => 'Treatment B2 (therapist)',
			'active' => true,
			'duration' => 2,
			'price' => 2,
			'tax' => 3,
			'role' => "therapist"
		));

	}
}