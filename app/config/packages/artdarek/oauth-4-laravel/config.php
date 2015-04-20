<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
	        // секрет поэтому и называется секретом, что его нельзя никому давать :-)
	        // подобные вещи нельзя сохранять в гит-репозитории, это локальные настройки
	        // которые не коммитятся в гит иначе возникает утечка
	        // каждый участник проекта (даже я) должен использовать собственные параметры
	        // client_id и client_secret
	        // это может быть обепечено за счет конфигурации local которая используется в "среде" local
            'client_id'     => '1377624389231652',
            'client_secret' => '5c2a8c89bdd11d670c68dea25e05ed3f',
            'scope'         => array(),
        ),

		/**
		 * Twitter
		 */
        'Twitter' => array(
	        'client_id'     => 'Your Twitter client ID',
	        'client_secret' => 'Your Twitter Client Secret',
	        // No scope - oauth1 doesn't need scope
        ),

		/**
		 * Google
		 */
        'Google' => array(
	        // то же самое замечание, что и про фейсбук
	        'client_id'     => '197273052726-8gi5rj2feju38pu0su7ngdj52rldvaap.apps.googleusercontent.com',
	        'client_secret' => 'E5o6uZ8JKwfDuAq0Uc31i21r',
	        'scope'         => array('userinfo_email', 'userinfo_profile'),
        ),


		/**
		 * Vkontakte
		 */
        'Vkontakte' => array(
	        // то же самое замечание, что и про фейсбук
	        'client_id'     => '4863508',
	        'client_secret' => 'BZA8onK3Bv3hXy2KunTW',
        ),

	)

);