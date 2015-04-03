<?php

class RoleTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users_role')->delete();

		$role = Role::create(array(
			'id' => 1,
			'name' => 'admin',
			'description' => 'Administrator.'
		));
		$role = Role::create(array(
			'id' => 2,
			'name' => 'doctor',
			'description' => 'Doctor.'
		));
		$role = Role::create(array(
			'id' => 3,
			'name' => 'therapist',
			'description' => 'Therapist.'
		));

		$role = Role::create(array(
			'id' => 4,
			'name' => 'accounting',
			'description' => 'Accounting.'
		));

		$role = Role::create(array(
			'id' => 5,
			'name' => 'cashier',
			'description' => 'Cashier.'
		));

		$role = Role::create(array(
			'id' => 6,
			'name' => 'user',
			'description' => 'Patients.'
		));
	}

}