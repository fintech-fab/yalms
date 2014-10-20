<?php

use Yalms\Models\Users\User;
use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;


class UserSignController extends \BaseController
{

    public function index()
    {
        return View::make('pages.user.index' );
    }

    public function register()
    {
        return View::make('registration');
    }


    public function  login()
    {
        $data = Input::all();

        $rules = [
            'phone' => 'required|min:11',
            'password' => 'required|min:8'
        ];

        $val = Validator::make($data, $rules);

        if( $val->fails())
        {
            return View::make('errors.validation')->with('errors', $val->messages()->toArray());
        }

        $user = User::login($data);
    }

    public function registration()
    {
        $data = Input::all();

        $rules = [
            'phone' => 'required|min:11',
            'password' => 'required|min:8|same:repeat_password',
            'repeat_password' => 'required|min:8'
        ];


	    $val = Validator::make($data, $rules);
	    if ($val->fails())
        {
	        return Redirect::to('registration')->withInput()->withErrors($val);
        }

        $user = User::register($data);

	    return Redirect::action('UserSignController@index');
    }

	public function loginFacebook()
	{
		/**
		 * Bootstrap the example
		 *
		 *require_once __DIR__ . '/bootstrap.php';
		 */
		$storage = new Session();
// Session storage
		$servicesCredentials['facebook']['key'] = '641605372625410';
		$servicesCredentials['facebook']['secret'] = 'ddcc4fda61288b868a6ab30eae14400c';
// Setup the credentials for the requests

		$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
		$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);

		$currentUri->setHost($_SERVER['HTTP_HOST']);
		$currentUri->setQuery('');

		$credentials = new Credentials(
			$servicesCredentials['facebook']['key'],
			$servicesCredentials['facebook']['secret'],
			$currentUri->getAbsoluteUri()
		);

// Instantiate the Facebook service using the credentials, http client and storage mechanism for the token
		/** @var $facebookService Facebook */
		$serviceFactory = new \OAuth\ServiceFactory();
		$facebookService = $serviceFactory->createService('facebook', $credentials, $storage, array());


		if (!empty($_GET['code'])) {
			// This was a callback request from facebook, get the token
			$token = $facebookService->requestAccessToken($_GET['code']);

			// Send a request with it
			$result = json_decode($facebookService->request('/me'), true);

			// Show some of the resultant data
			echo 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];

			$user = User::register($result);
		} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
			$url = $facebookService->getAuthorizationUri();
			header('Location: ' . $url);
		} else {
			$url = $currentUri->getRelativeUri() . '?go=go';
			echo "<a href='$url'>Login with Facebook!</a>";
		}
	}

} 