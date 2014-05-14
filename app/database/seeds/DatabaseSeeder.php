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
		User::create([
			'username' => 'test',
			'email' => 'test@mail.com',
			'password' => Hash::make('pass')
			]);

	}

}
