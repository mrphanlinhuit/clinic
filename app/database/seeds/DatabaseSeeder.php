<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('GroupTableSeeder');
		$this->call('RoleTableSeeder');
		$this->call('UserTableSeeder');
		$this->call('BondtypeTablesSeeder');
		$this->call('ProvidersSeeder');
		$this->call('InvoicesProvidersSeeder');
	}

}
