<?php

// это здесь зачем?
Use app\controllers\Api\User\UserController;

// название контроллеру дать "нормальное" :-)
class UserSignController2 extends BaseController
{
	public function index()
	{
		// всегда делать неймспейсы при работе с сессией
		// например Session::has('network.auth.phone')
		// Session::put('network.auth.phone', ...);
		// потому что просто "телефон" - это просто телефон
		// любая другая часть системы может использовать phone для чего-либо
		// и возникнет неуловимая ошибка
		// поэтому всегда-всегда нужно задавать целевой неймспейс
		if (!Session::has('phone')) {
			return $this->getPhoneNumber();
		}

		// то же самое замечание
		// и еще. чтобы было легче, для записи и чтения из сессии
		// лучше сделать отдельный класс-компонент
		// и тогда получится что-то вроде
		// $userNetwork = new UserNetworkComponent();
		// $userNetwork->hasPhone()
		// $userNetwork->hasSocialInfo()
		// это общее правило - контроллер не должен сам ничего делать
		// он должен только принимать запросы и вызывать другие компоненты
		if (!Session::has('socialInfo')) {
			return $this->getSocialInfo();
		}

		return $this->getMissingInfo();
	}

	public function newRegistration()
	{
		Session::flush();
		// название давать нормальное, а не нумерованное
		// вобщем никогда так не делать метод1, метод2, метод3 и т.п.
		// все названия должны по смыслу совпадать с выполняемым внутри действием
		return Redirect::to('registration2');
	}

	public function getPhoneNumber()
    {
	    return View::make('registration.getphone');
    }

	public function validatePhoneNumber()
	{
		$data = Input::all();
		$rules = ['phone' => 'required|min:11|unique:users'];
		$val = Validator::make($data, $rules);

		if( $val->fails())
		{
			return Redirect::back()->withErrors($val)->withInput();
		}

		Session::put('phone', $data['phone']);
		// комментариев нет, название "странное", я совсем не понимаю куда идет редирект
		// а должен понимать не глядя дальше
		return Redirect::to('registration2');
	}

	public function getSocialInfo()
    {
	    return View::make('registration.getsocialinfo');
    }

	public function skipSocial()
    {
	    // ох как непонятно...
	    // "эй, какая у тебя социальная информация?" - "None!" :-)
	    Session::put('socialInfo', 'None');
	    return Redirect::to('registration2');
    }

	// тут getMissingInfo
	public function getMissingInfo()
    {
	    // а тут просто getmissing
	    // что-то кажется потеряно, судя по названию... но что?
	    // вобщем с названиями нужно поработать
	    // или писать подробные комментарии
	    return View::make('registration.getmissing');
    }

	public function finish()
    {
	    return View::make('registration.finish');
    }


	/**
	 * Login user with facebook
	 *
	 * @return void
	 */

	public function loginWithFacebook() {

		// get data from input
		$code = Input::get( 'code' );

		// get fb service
		$fb = OAuth::consumer( 'Facebook' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {

			// This was a callback request from facebook, get the token
			$token = $fb->requestAccessToken( $code );

			// Send a request with it
			// отлично, а что если $fb->request ничего не дал в ответ?
			$result = json_decode( $fb->request( '/me' ), true );

			// зачем здесь выводить информацию в браузер?
			$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			echo $message. "<br/>";

			foreach($result as $key=>$element){
				Session::put($key, $element);
			}

			// и снова: "эй, какая у тебя социальная информация?" - "Facebook!" :-)
			// название социальной сети facebook это не "социальная информация", то есть не socialInfo
			// напомню что лучше сделать отдельный компонент, у которого будут внятного вида методы
			// например
			// $userNetwork = new UserNetworkComponent();
			// $userNetwork->setUsesNetwork('facebook')
			// что будет значить "установить используемую сеть - фейсбук"
			// или
			// $userNetwork->withNetwork('facebook')
			// что будет значить "работать будем с сетью фейсбук"
			Session::put('socialInfo', 'Facebook');

			// повторюсь: по коду должно быть понятно, куда система идет дальше
			// из названия registration2 - непонятно ничего, можно подумать что это вторая регистрация
			// или регистрация другим способом
			return Redirect::to('registration2');

		}
		// if not ask for permission first
		else {
			// get fb authorization
			$url = $fb->getAuthorizationUri();

			// return to facebook login url
			return Redirect::to( (string)$url );
		}
	}

	// те же замечания что и к фейсбуку выше
	public function loginWithVK() {

		// get data from input
		$code = Input::get( 'code' );

		// get VK service
		$VKService = OAuth::consumer( 'Vkontakte' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {

			// This was a callback request from VK, get the token
			$token = $VKService->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $VKService->request( 'https://api.vk.com/method/users.get' ), true );

			foreach($result['response'][0] as $key=>$element){
				Session::put($key, $element);
			}
			Session::put('socialInfo', 'Vkontakte');
			return Redirect::to('registration2');
		}
		// if not ask for permission first
		else {
			// get VKService authorization
			$url = $VKService->getAuthorizationUri();

			// return to VK login url
			return Redirect::to( (string)$url );
		}
	}

}