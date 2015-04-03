<?php

class BondtypeTablesSeeder extends Seeder {

    /**
     * Run seed
     * @return void
     */
    public
    function run() {

        BondType::create(array(
            "name" => '10 sessions Christmas Bond',
            "tax" => 0.21,
            "price" => 100,
            "session" => 10,
            "timetouse" => 60,
            "status" => 1
        ));

        BondType::create(array(
            "name" => '10 sessions Bond',
            "tax" => 0.21,
            "price" => 130,
            "session" => 10,
            "timetouse" => 365,
            "status" => 1
        ));


        BondType::create(array(
            "name" => '10 sessions Summer Bond',
            "tax" => 0.21,
            "price" => 50,
            "session" => 5,
            "timetouse" => 30,
            "status" => 0
        ));
    }

}