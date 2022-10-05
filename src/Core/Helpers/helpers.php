<?php
defined("APPLICATION_DIR") or exit("No direct Accesss here !");

use Core\Libs\Logger;
use Core\Libs\Redirector;
use Core\Libs\Support\Facades\Config;
use Core\Libs\Support\NormalizeData;
use Core\Libs\Uri;
use Core\Bootstrap\DiContainer;
use Core\Libs\Validator;
use Core\Libs\Csrf;
use Core\Libs\View;
use Core\Libs\Request;
use Core\Libs\Support\HigherOrderTapProxy;
use Core\Libs\Support\Arr;
use Core\Libs\XssSecure;
use Illuminate\Support\Collection;
use Core\Libs\Support\NormalizeData as Normalize;
use Core\Libs\Encryption\Encrypter;
use Core\Libs\Encryption\EncryptionService;

// ---  System  ---
/**
 * @param string $version
 * @return  bool
 */
function is_php($version = "7.0.0")
{
	static $phpVer;
	$version = (string)$version;

	if (!isset($phpVer[$version])) {
		$phpVer[$version] = version_compare(PHP_VERSION, $version) >= 0;
	}

	return $phpVer[$version];
}

/**
 * Get App class instance
 */
if (!function_exists("app")) {
	/**
	 * DI Container
	 *
	 * @param $name
	 * @return mixed
	 * @throws ReflectionException
	 */
	function app($name)
	{
		$container = new DiContainer();

		return $container->get($name);
	}
}

if (!function_exists("dd")) {
	/**
	 * @param mixed ...$value
	 */
	function dd(...$value)
	{
		dump(...$value);
		exit();
	}
}

if (!function_exists("config")) {
	/**
	 * @param $key
	 * @return mixed
	 * @throws Exception
	 */
	function config($key, $domain = Config::DOMAIN)
	{
		return Config::get($key, $domain);
	}
}

if (!function_exists("isClosure")) {
	/**
	 * @param $suspected_closure
	 * @return bool
	 * @throws ReflectionException
	 */
	function isClosure($suspected_closure)
	{
		if (is_callable($suspected_closure)) {
			$reflection = new ReflectionFunction($suspected_closure);

			return $reflection->isClosure();
		}

		return false;
	}
}

if (!function_exists("value")) {
	/**
	 * Return the default value of the given value.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}

if (!function_exists("dot_notation_dir")) {
	/**
	 * @param $name
	 * @return string|string[]
	 */
	function dot_notation_dir($name)
	{
		if (strpos($name, ".") !== false) {
			return str_replace(".", DIRECTORY_SEPARATOR, $name);
		}

		return $name;
	}
}

if (!function_exists("client_ip")) {
	/**
	 * @return mixed
	 */
	function client_ip()
	{
		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}

		return $ip;
	}
}

//---       Data manipulations  ---

if (!function_exists("tap")) {
	/**
	 * Call the given Closure with the given value then return the value.
	 *
	 * @param mixed $value
	 * @param callable|null $callback
	 * @return mixed
	 */
	function tap($value, $callback = null)
	{
		if (is_null($callback)) {
			return new HigherOrderTapProxy($value);
		}

		$callback($value);

		return $value;
	}
}

if (!function_exists("collect")) {
	/**
	 * Create a collection from the given value.
	 *
	 * @param mixed $value
	 * @return Collection
	 */
	function collect($value = null)
	{
		return new Collection($value);
	}
}

if (!function_exists("data_get")) {
	/**
	 * Get an item from an array or object using "dot" notation.
	 *
	 * @param mixed $target
	 * @param string|array|int $key
	 * @param mixed $default
	 * @return mixed
	 */
	function data_get($target, $key, $default = null)
	{
		if (is_null($key)) {
			return $target;
		}

		$key = is_array($key) ? $key : explode(".", $key);

		while (!is_null($segment = array_shift($key))) {
			if ($segment === "*") {
				if ($target instanceof Collection) {
					$target = $target->all();
				} elseif (!is_array($target)) {
					return value($default);
				}

				$result = [];

				foreach ($target as $item) {
					$result[] = data_get($item, $key);
				}

				return in_array("*", $key) ? Arr::collapse($result) : $result;
			}

			if (Arr::accessible($target) && Arr::exists($target, $segment)) {
				$target = $target[$segment];
			} elseif (is_object($target) && isset($target->{$segment})) {
				$target = $target->{$segment};
			} else {
				return value($default);
			}
		}

		return $target;
	}
}

if (!function_exists("data_set")) {
	/**
	 * Set an item on an array or object using dot notation.
	 * From helpers Laravel.com
	 * @param mixed $target
	 * @param string|array $key
	 * @param mixed $value
	 * @param bool $overwrite
	 * @return mixed
	 */
	function data_set(&$target, $key, $value, $overwrite = true)
	{
		$segments = is_array($key) ? $key : explode(".", $key);

		if (($segment = array_shift($segments)) === "*") {
			if (!Arr::accessible($target)) {
				$target = [];
			}

			if ($segments) {
				foreach ($target as &$inner) {
					data_set($inner, $segments, $value, $overwrite);
				}
			} elseif ($overwrite) {
				foreach ($target as &$inner) {
					$inner = $value;
				}
			}

		} elseif (Arr::accessible($target)) {
			if ($segments) {
				if (!Arr::exists($target, $segment)) {
					$target[$segment] = [];
				}

				data_set($target[$segment], $segments, $value, $overwrite);
			} elseif ($overwrite || !Arr::exists($target, $segment)) {
				$target[$segment] = $value;
			}

		} elseif (is_object($target)) {
			if ($segments) {
				if (!isset($target->{$segment})) {
					$target->{$segment} = [];
				}

				data_set($target->{$segment}, $segments, $value, $overwrite);
			} elseif ($overwrite || !isset($target->{$segment})) {
				$target->{$segment} = $value;
			}
		} else {
			$target = [];

			if ($segments) {
				data_set($target[$segment], $segments, $value, $overwrite);
			} elseif ($overwrite) {
				$target[$segment] = $value;
			}
		}

		return $target;
	}
}

if (!function_exists("array_map_recursive")) {
	/**
	 * @param callable $func
	 * @param array $array
	 * @return mixed
	 */
	function array_map_recursive(callable $func, array $array)
	{
		return filter_var($array, FILTER_CALLBACK, ["options" => $func]);
	}
}

/*
 *  === URL / URI / Redirect / Routing ===
 */

if (!function_exists("uri")) {
	/**
	 * @return mixed
	 * @throws ReflectionException
	 */
	function uri()
	{
		return app(Uri::class);
	}
}

if (!function_exists("route")) {
	/**
	 * @param $routename
	 * @param array $params
	 * @param null $request_method
	 * @return mixed
	 * @throws ReflectionException
	 */
	function route($routename, array $params = [], $request_method = null)
	{
		$container = app(Uri::class);

		return $container->route($routename, $params, $request_method)->route;
	}
}

if (!function_exists("route_url")) {
	/**
	 * @param $routename
	 * @param array $params
	 * @param null $request_method
	 * @return mixed
	 * @throws ReflectionException
	 */
	function route_url($routename, array $params = [], $request_method = null)
	{
		//$container = app(Router::class);
		$container = app(Uri::class);

		return site_url(
			$container->route($routename, $params, $request_method)->route
		);
	}
}

if (!function_exists("redirect")) {
	/**
	 * redirect()->to('home')->with('msg', 'Login Page')
	 * redirect()->route('home')
	 * redirect()->away('http://abv.bg')
	 * redirect( 'ErrorPage/show/404' )
	 * redirect( route(name, [agr, arg1]) )
	 * @method string away($url)
	 */
	function redirect($uri = null)
	{
		$container = app(Redirector::class);
		if ($uri !== null) {
			$container->to($uri);
		} else {
			return $container;
		}
	}
}

// ---     Request Helpers      --- //

if (!function_exists("request_post")) {
	/**
	 * @param $name
	 * @param null $normalize
	 * @return mixed
	 */
	function request_post($name, $normalize = null)
	{
		$container = app(Request::class);

		return $container->post($name, $normalize);
	}
}

if (!function_exists("request_get")) {
	/**
	 * @param $name
	 * @param null $normalize
	 * @return mixed
	 */
	function request_get($name, $normalize = null)
	{
		$container = app(Request::class);

		return $container->get($name, $normalize);
	}
}

// ---      Cookies ---

if (!function_exists("set_cookie")) {
	/**
	 * @param $name
	 * @param $value
	 * @return mixed
	 */
	function set_cookie($name, $value)
	{
		$container = app(Request::class);

		$container->set_cookie($name, $value);
	}
}

if (!function_exists("get_cookie")) {
	/**
	 * @param $name
	 * @return mixed
	 */
	function get_cookie($name)
	{
		$container = app(Request::class);

		return $container->cookie($name);
	}
}

/*  -- Validation Helpers --- */

if (!function_exists("validator")) {
	/**
	 * @param $data
	 * @return mixed
	 * @throws ReflectionException
	 */
	function validator($data)
	{
		$validator = app(Validator::class);

		return $validator->for($data);
	}
}

if (!function_exists("oldValue")) {
	/**
	 * При неуспешна валидация връща стойността на полето.
	 * @param $field
	 * @param bool $html_decode
	 * @return mixed|null
	 * @throws Exception
	 */
	function oldValue($field, $html_decode = true)
	{
		$Obj = Validator::getInstance();

		if ($Obj->hasErrors() === true) {
			return $html_decode
				? htmlspecialchars_decode($Obj->request->input($field))
				: $Obj->request->input($field);
		}

		return "";
	}
}

if (!function_exists("has_error")) {
	/**
	 * @param null $field
	 * @return bool
	 * @throws ReflectionException
	 */
	function has_error($field = null): bool
	{
		$validator = app(Validator::class);

		return $validator->hasErrors($field);
	}
}

if (!function_exists("validation_error")) {
	/**
	 * Показва съобщеие за грешки при валидация на форма
	 * @param $field
	 * @return string
	 * @throws Exception
	 */
	function validation_error($field): string
	{
		$Obj = Validator::getInstance();

		if ($Obj->hasErrors($field) === true) {
			return $Obj->errors(
				$field,
				"",
				"",
				'<span style="color:#c9302c">%s</span>'
			);
		}

		return "";
	}
}

//---------------  Form Helpers ------------------------
/**
 * csrf_field()
 * using of blade directive @csrf
 */
if (!function_exists("csrf_field")) {
	function csrf_field()
	{
		$container = app(Csrf::class);

		$container->csrf_field();
	}
}
/**
 * Alias of csrf_field()
 * using of blade directive @csrf
 */
if (!function_exists("csrf")) {
	function csrf()
	{
		csrf_field();
	}
}

if (!function_exists("method_field")) {
	/**
	 * Generate a form field to spoof the HTTP verb used by forms.
	 *
	 * @param string $method
	 * @return string
	 */
	function method_field($method): string
	{
		return '<input type="hidden" name="_method" value="' .
			$method .
			'">' .
			PHP_EOL;
	}
}

//---       Render View  Helpers  ---
if (!function_exists("setLayout")) {
	/**
	 * use: setLayout('dashboard')->render('result', $data);
	 *
	 * @param $layout
	 * @return mixed
	 * @throws ReflectionException
	 */
	function setLayout($layout)
	{
		$container = app(View::class);

		return $container->setLayout($layout);
	}
}

/*
 *  Render View file
 */
if (!function_exists("view")) {
	/**
	 *  Render View
	 *
	 * @param $name
	 * @param array $data
	 * @throws ReflectionException
	 */
	function view($name, $data = [])
	{
		$container = app(View::class);

		return $container->render($name, $data);
	}
}

if (!function_exists("partial")) {
	function partial($partial): string
	{
		if (strpos($partial, ".")) {
			$partial = str_replace(".", DIRECTORY_SEPARATOR, $partial);
		}

		$path = VIEW_DIR . "Partials" . DIRECTORY_SEPARATOR . $partial . ".php";

		if (file_exists($path)) {
			return $path;
		}

		throw new Exception(sprintf("The (%s) not found", $path));
	}
}

if (!function_exists("esc")) {
	/**
	 * Escape HTML special characters in a string.
	 *
	 * @param $data
	 * @param null $filter
	 * @return string
	 */
	function esc($data, $filter = null)
	{
		if ($filter) {
			$data = NormalizeData::filter($data, $filter);
		}

		return htmlspecialchars($data, ENT_QUOTES, "UTF-8", true);
	}
}

if (!function_exists("resc")) {
	/**
	 * Escape HTML special characters recursive in an array.
	 * @param $data
	 * @return array
	 */
	function resc($data)
	{
		return array_map(function ($item) {
			//Recursive
			if (is_array($item)) {
				return resc($item);
			}

			return htmlspecialchars($item, ENT_QUOTES, "UTF-8", true);
		}, $data);
	}
}

if (!function_exists("xss")) {
	/**
	 * Xss clean
	 * @param $item
	 * @return mixed|string
	 */
	function xss($item)
	{
		return XssSecure::xss_clean($item);
	}
}

if (!function_exists("xss_clean")) {
	/**
	 * @param $item
	 * @return mixed|string
	 */
	function xss_clean($item)
	{
		return XssSecure::xss_clean($item);
	}
}

if (!function_exists("escd")) {
	/**
	 * htmlspecialchars_decode
	 * @param string $data
	 * @return string
	 */
	function escd($data = "")
	{
		return htmlspecialchars_decode($data, ENT_QUOTES);
	}
}

// --- Directory & file helpers ---

if (!function_exists("erdir")) {
	/**
	 * empty recursive directory
	 * Delete All files from directory recursively
	 * @param $dir
	 * @return bool
	 */
	function erdir($dir)
	{
		if (!realpath($dir)) {
			return false;
		}

		$del = [];

		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$dir,
				RecursiveDirectoryIterator::SKIP_DOTS
			),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileinfo) {
			if ($fileinfo->isFile()) {
				$del[] = unlink($fileinfo->getRealPath());
			}
		}

		if (in_array(false, $del)) {
			return false;
		}

		return true;
	}
}

if (!function_exists("rrmdir")) {
	/**
	 *
	 * Recursive delete dir
	 *
	 * @param $dir
	 * @return bool
	 */
	function rrmdir($dir)
	{
		if (!realpath($dir)) {
			return false;
		}

		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$dir,
				RecursiveDirectoryIterator::SKIP_DOTS
			),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileinfo) {
			$todo = $fileinfo->isDir() ? "rmdir" : "unlink";
			$todo($fileinfo->getRealPath());
		}
		if (rmdir($dir)) {
			return true;
		}

		return false;
	}
}

if (!function_exists("get_file_extension")) {
	function get_file_extension($file)
	{
		$info = new SplFileInfo($file);
		return $info->getExtension();
	}
}

// ============== CRYPT ==========================================//

if (!function_exists("crypt_generate_key")) {
	/**
	 * @return string
	 * @throws Exception
	 */
	function crypt_generate_key()
	{
		$cipher = Config::getConfigFromFile("cipher") ?? "AES-256-CBC";

		return base64_encode(Encrypter::generateKey($cipher));
	}
}

if (!function_exists("encrypt")) {
	/**
	 * @param $value
	 * @param bool $serialize
	 * @return mixed
	 */
	function encrypt($value, $serialize = true)
	{
		return app(EncryptionService::class)->encrypter->encrypt(
			$value,
			$serialize
		);
	}
}

if (!function_exists("decrypt")) {
	/**
	 * @param $value
	 * @param bool $serialize
	 * @return mixed
	 * @throws ReflectionException
	 */
	function decrypt($payload, $unserialize = true)
	{
		return app(EncryptionService::class)->encrypter->decrypt(
			$payload,
			$unserialize
		);
	}
}

if (!function_exists("passwordHash")) {
	/**
	 * @param $str
	 * @return bool|string
	 */
	function passwordHash($str)
	{
		$opt = ["cost" => 10];
		return password_hash($str, PASSWORD_BCRYPT, $opt);
	}
}

//--- Array helpers ---
if (!function_exists("trimValues")) {
	/**
	 * Recursively trim values in array
	 * @param $item
	 * @return array|string
	 */
	function trimValues($item)
	{
		if (!is_array($item)) {
			return trim($item);
		}

		return array_map("trimValues", $item);
	}
}

if (!function_exists("data_normalize")) {
	/**
	 *
	 * @param $item
	 * @param $rules
	 *
	 */
	function data_normalize(&$item, $rules)
	{
		if (!is_array($item)) {
			$item = Normalize::filter($item, $rules);
		} else {
			array_walk_recursive($item, function (&$v) use ($rules) {
				$v = Normalize::filter($v, $rules);
			});
		}
	}
}

if (!function_exists("filter")) {
	function filter($item, $rules)
	{
		if (!is_array($item)) {
			return Normalize::filter($item, $rules);
		}
		return array_map("filter", $item);
	}
}

if (!function_exists("array_where")) {
	/**
	 * Filter the array using the given callback.
	 *
	 *  $filtered = array_where($a, function ($value, $key){
	 *    return $value > 20;
	 *   });
	 *
	 *
	 * @param array $array
	 * @param callable $callback
	 * @return array
	 */
	function array_where($array, callable $callback)
	{
		return Arr::where($array, $callback);
	}
}

if (!function_exists("array_has")) {
	/**
	 * Check if an item or items exist in an array using "dot" notation.
	 *
	 * @param ArrayAccess|array $array
	 * @param string|array $keys
	 * @return bool
	 */
	function array_has($array, $keys)
	{
		return Arr::has($array, $keys);
	}
}

if (!function_exists("array_pluck")) {
	/**
	 * Pluck an array of values from an array.
	 *
	 *  $array = [
	 *      ['developer' => ['id' => 1, 'name' => 'Taylor']],
	 *      ['developer' => ['id' => 2, 'name' => 'Abigail']],
	 *   ];
	 *
	 *   $names = array_pluck($array, 'developer.name', 'developer.id');
	 *
	 * @param array $array
	 * @param string|array $value
	 * @param string|array|null $key
	 * @return array
	 */
	function array_pluck($array, $value, $key = null)
	{
		return Arr::pluck($array, $value, $key);
	}
}

if (!function_exists("array_only")) {
	/**
	 * Get a subset of the items from the given array.
	 *
	 * @param array $array
	 * @param array|string $keys
	 * @return array
	 */
	function array_only($array, $keys)
	{
		return Arr::only($array, $keys);
	}
}

if (!function_exists("array_random")) {
	/**
	 * $array = [1, 2, 3, 4, 5];
	 * $random = array_random($array);
	 * @param $array
	 * @return mixed
	 */
	function array_random($array)
	{
		$length = count($array);

		$key = rand(0, $length - 1);

		return $array[$key];
	}
}

if (!function_exists("array_collapse")) {
	/**
	 * Collapse an array of arrays into a single array.
	 * $array = Arr::collapse([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
	 *    [1, 2, 3, 4, 5, 6, 7, 8, 9]
	 * @param $array
	 * @return array
	 */
	function arrray_collapse($array)
	{
		return Arr::collapse($array);
	}
}

// Logging
if (!function_exists("logger")) {

	function logger($channel='')
	{
		$logger =  new Logger($channel);

		return $logger->getLogger();
	}
}
