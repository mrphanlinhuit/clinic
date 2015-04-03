<?php

class InvoicesProvidersSeeder extends Seeder {

	/**
	 * Run seed
	 * @return void
	 */
	public function run()
	{
		$invoice = InvoicesProviders::create(array(
		 "provider_id" => 1,
		 "number" => '1238109841',
		 "description" => 'First milestone',
		 "date" =>  '2014-01-01',
		 "taxes" => 0.2,
		 "amount"=>  500
		 ));
		// Movements::create(array(
		// 	"invoices_providers_id" => $invoice->id,
		// 	"amount" => (-1 * ($invoice->amount + ($invoice->amount * $invoice->taxes)))
		// 	));

		$invoice =InvoicesProviders::create(array(
		 "provider_id" => 1,
		 "number" => 'F212FaA34f',
		 "description" => 'January 2014 water',
		 "date" =>  '2014-01-01',
		 "taxes" => 0.2,
		 "amount"=>  20
		));

		// Movements::create(array(
		// 	"invoices_providers_id" => $invoice->id,
		// 	"amount" => (-1 * ($invoice->amount + ($invoice->amount * $invoice->taxes)))
		// 	));


		$invoice = InvoicesProviders::create(array(
		 "provider_id" => 1,
		 "number" => 'El-64361241',
		 "description" => 'January 2014 electrical power',
		 "date" =>  '2014-01-01',
		 "taxes" => 0.2,
		 "amount"=>  50
		));
		
		// Movements::create(array(
		// 	"invoices_providers_id" => $invoice->id,
		// 	"amount" => (-1 * ($invoice->amount + ($invoice->amount * $invoice->taxes)))
		// 	));
	}
}