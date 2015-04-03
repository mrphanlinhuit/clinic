<?php

class ProvidersSeeder extends Seeder {

    /**
     * Run seed
     * @return void
     */
    public
    function run() {
        Provider::create(array(
            "name" => 'Bee Sight Soft',
            "note" => 'The best company in the world',
        ));
        Provider::create(array(
            "name" => 'Electricity company',
            "note" => 'They provide electricity!'
        ));
        Provider::create(array(
            "name" => 'Water company',
            "note" => 'They provide water!'
        ));

    }
}