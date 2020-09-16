<?php 

return [

	/**
	 * Merchant account number
	 */
	"account_number" 		=>	env('HUBTEL_MERCHANT_ACCOUNT_NUMBER'),

	/**
	 * Login credentials for hubtel api
	 *
	 */
	"api_key" => [
		"client_id" 		=>	env('HUBTEL_MERCHANT_ACCOUNT_CLIENT_ID'),
		"client_secret"		=>	env('HUBTEL_MERCHANT_ACCOUNT_CLIENT_SECRET')
	],

	/**
	 * business details
	 */
	"business" => [
		"name" 				=>	env('APP_NAME')
	]
];