<?php
/*
 * Валидация
 *
 * Пример:
 * В контролера
 *
 * $validator = new \Libs\Validator
 *
 * $validator->for($data)
 *          ->make('checkin', 'пристигане' ,['required', 'min:2', 'max:15'])
 *          ->make('checkout', 'заминаване' ,['min:2', 'max:15'])->run();
 *
 * if($validator->hasErrors()){ .... }
 *
 * Във View:
 * echo $errors->all()
 *
 */

namespace Core\Libs;

use Core\Libs\Support\Arr;
use Core\Libs\Support\MessageBag;
use Core\Libs\Validator\Rules\ValidationRules;
use Core\Libs\Validator\ValidationData;
use Core\Libs\Validator\ValidationDataParser;
use Exception;
use JsonException;
use ReflectionException;

class Validator
{
	use ValidationDataParser, ValidationRules;

	/**
	 * @var
	 */
	public static ?Validator $instance = null;
	/**
	 * new Message();
	 * @var Message
	 */
	public Message $message;
	/**
	 * @var MessageBag
	 */
	public MessageBag $errors;
	/**
	 * old value
	 * @var
	 */
	public $old;
	/**
	 * Raw Data for validation
	 * @var
	 */
	public array $data = [];
	/**
	 * Container for  custom error message for All fields under validation.
	 * $messages = ['required' => 'The field is cannot be empty']
	 * @var
	 */
	public array $ownMessages = [];
	/**
	 * Container for  custom error message only for a specific field.
	 * $messages = ['email' =>[required' => 'The field is cannot be empty']]
	 * @var
	 */
	public array $ownFieldMessages = [];
	/**
	 * @var string
	 */
	public string $_prefix_tag = '<p>';
	/**
	 * @var string
	 */
	public string $_postfix_tag = '</p>';
	/**
	 * @var string
	 */
	public string $_alert_format = '<div class="alert alert-danger" role="alert">%s</div>';
	/**
	 * @var
	 */
	public $error_string;
	/**
	 * @var array
	 */
	private array $parsed_rule_data = [];
	/**
	 * @var array
	 */
	private array $file_rules = ['file', 'filesize', 'required'];

	/**
	 * Validator constructor.
	 * @throws Exception
	 */
	protected function __construct()
	{
		$this->message = new Message();
		$this->errors = new MessageBag();
	}

	/**
	 * singleton
	 *
	 * @return Validator
	 * @throws Exception
	 */
	public static function getInstance(): Validator
	{
		if (self::$instance === null) {

			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Fоr what is this validation
	 * @param $data
	 * @return $this
	 */
	public function for($data): Validator
	{
		if ($data instanceof Request) {
			$this->data = (array)array_merge($this->data, $data->input());

		} else {
			$this->data = array_merge($this->data, $data);
		}

		return $this;
	}

	/**
	 * @param $field
	 * @param null $label
	 * @param null $rules
	 * @param null $customMsg
	 * @return $this
	 */
	public function ruleFor($field, $label = null, $rules = null, $customMsg = null): Validator
	{
		return $this->make($field, $label, $rules, $customMsg);
	}

	/**
	 * @param $field
	 * @param null $label
	 * @param null $rules
	 * @param null $customMsg
	 * @return $this
	 */
	public function make($field, $label = null, $rules = null, $customMsg = null): Validator
	{
		// parse $field if input in an array and have wildcard like (emails.*)
		// will return array of replaced field key in dot notation => [emails.1, emails.2, emails.3]
		// with their values from

		if (is_array($field)) {

			/*
			 * [
				'email.*'=>[
					'label'=>'email',
					'rules'=>['required', 'max:10'],
					'message'=>['require'=>'message']
				   ]
				]
			 */

			foreach ($field as $key => $value) {
				$implicitAttributesData = (ValidationData::initializeAndGatherData($key, $this->data));

				foreach ($implicitAttributesData as $field_name => $dataforvalidation) {
					$this->parsed_rule_data[$field_name] = $this->createRulesDataArray($field_name, $value['label'], $dataforvalidation, $value['rules']);

					if ($value['message'] && $customMsg === null) {
						// $custoMsg = ['field.rule'=>'message']
						$message = $this->parseCustomMsgFromArray($field_name, $value['message']);
						$this->ownFieldMessages[$field_name] = $message;
					}

					data_set($this->old, $field_name, $dataforvalidation);
				}

			}

		} elseif (strpos($field, '.') !== false) {

			$implicitAttributesData = ValidationData::initializeAndGatherData($field, $this->data);

			foreach ($implicitAttributesData as $field_name => $dataforvalidation) {
				$this->parsed_rule_data[$field_name] = $this->createRulesDataArray($field_name, $label, $dataforvalidation, $rules);
				data_set($this->old, $field_name, $dataforvalidation);

				if ($customMsg !== null) {
					$message = $this->parseCustomMsgFromArray($field_name, $customMsg);
					$this->ownFieldMessages[$field_name] = $message;

				}
			}

		} else {
			$this->parsed_rule_data[$field] = $this->createRulesDataArray($field, $label, $this->data[$field] ?? "", $rules);
			data_set($this->old, $field, $this->data[$field] ?? "");

			if ($customMsg !== null) {
				$message = $this->parseCustomMsgFromArray($field, $customMsg);
				$this->ownFieldMessages[$field] = $message;

			}
		}

		return $this;

	}

	/**
	 * @return bool
	 * @throws ReflectionException
	 */
	public function run(): bool
	{
		//не искам да валидирам празни данни
		/*if (empty($this->data)) {
			return false;
		}*/
		if (count($this->parsed_rule_data) > 0) {
			foreach ($this->parsed_rule_data as $data) {
				$dataForValidation = $data['value'];

				foreach ($data['rules'] as $_rules) {
					if (isClosure($_rules) === false) {
						// parsed rules now is ['rule'=>'somerule', 'arg'=>'argument']
						$parsedRules = $this->parseRules($_rules);
						$rule = $parsedRules['rule'];
						$arg = $parsedRules['arg'];
						// data validation
						$run = $this->$rule($dataForValidation, $arg);
						$label = $this->parseFieldLabel($data['field'], $data['label'], $rule);

						// array with errors
						if ($run === false && $rule !== 'nullable') {
							// delete old value
							Arr::forget($this->old, $data['field']);
							// if field is required
							if ($rule === 'required') {
								unset($this->errors->{$data['field']});
								$this->errors->{$data['field']} = $this->_msg($data['field'], 'required', $label, $arg);

								break;
							}
							$this->errors->{$data['field']} = $this->_msg($data['field'], $rule, $label, $arg);

						}
						// Не валидираме nullable
						if ($rule === 'nullable' && $run === true) {
							$this->errors->unset($data['field']);
						}

					} else {
						/*
						 * if validation rule is anonymous function
						 * [function($atribute, $value){ ...... }]
						*/
						$label = ($data['label']) ?: $data['field'];
						$a = call_user_func_array($_rules, [$label, $dataForValidation]);

						if (is_string($a)) {
							$this->errors->{$data['field']} = $a;
							Arr::forget($this->old, $data['field']);
						}
					}

				} //end foreach ( $data['rules'] as $rule => $arg )

			}
		}

		$has_errors = $this->errors->any();

		return $has_errors === false;
	}

	protected function parseFieldLabel($field, $label, $rule)
	{
		if (isset($this->validatedFilename) && strpos($label, ':') !== false && $rule !== 'file') {
			$label = $this->validatedFilename;

		} elseif (in_array($rule, $this->file_rules) && strpos($label, ':') !== false) {
			$label = substr($label, 1);

		} else {
			$label = ($label !== '') ? $label : $field;

		}

		return $label;
	}

	/**
	 * _msg
	 *
	 * @param $field
	 * @param $rule
	 * @param null $label
	 * @param null $arg
	 * @return mixed
	 * @throws Exception
	 */
	private function _msg($field, $rule, $label = null, $arg = null)
	{
		/*if(isset($this->validatedFilename) && $label == ':file' && $rule!=='file'){
			$label = $this->validatedFilename;
		}*/
		$arg = $this->getComparableFieldName($rule, $arg);
		// 1. if have a message for specific field
		if (isset($this->ownFieldMessages[$field][$rule])) {
			$message[$rule] = str_replace(['{label}', '{arg}'], [$label, $arg], $this->ownFieldMessages[$field][$rule]);

		} elseif (isset($this->ownMessages[$rule])) {
			// 2. if have a message for specific rule
			$message[$rule] = str_replace(['{label}', '{arg}'], [$label, $arg], $this->ownMessages[$rule]);

		} else {
			$message[$rule] = str_replace(['{label}', '{arg}'], [$label, $arg], $this->message->get('Validations')->line($rule));
		}
		return $message[$rule];
	}

	/**
	 * @param $rule
	 * @param $arg
	 * @return mixed
	 */
	protected function getComparableFieldName($rule, $arg)
	{
		switch ($rule) {
			case 'different':
			case 'match':
				$arg = ($this->parsed_rule_data[$arg]['label']) ? $this->parsed_rule_data[$arg]['label'] : $arg;

				break;

			// не искам да показва името на плето а неговата стойност / дата след 2016-05-20 /
			case 'after':
			case 'before':
				if (!$this->date($arg)) {
					$arg = $this->parsed_rule_data[$arg]['value'];
				}

				break;

		}

		return $arg;
	}

	/**
	 * @param array $messages
	 * @return $this
	 */
	public function message(array $messages)
	{
		/*
		 * array:1 [▼
			  "field" => array:2 [▼
				"required" => "The field is cannot be empty"
				"valid" => "Must be Valid Email address"
			  ]
			]
		 */
		// $custoMsg = ['field.rule'=>'message']
		// return $message[$rule]

		foreach ($messages as $field => $msg) {

			// ['email' => 'O Need  a valid email address']
			// Message for all fields under validation
			if (is_string($msg)) {
				$this->ownMessages[$field] = $msg;

			} elseif (is_array($msg)) {
				/*
					2. ['email.*.*' =>[
							'email' => 'Need  a valid email address',
							'max' => 'The {label} must be less then {arg}'
						  ]
						]
					message for specific field
				*/
				if (strpos($field, '.') !== false) {
					// have a wildcard
					$implicitAttributesData = (ValidationData::initializeAndGatherData($field, $this->data));
					$fields = array_keys($implicitAttributesData);

					foreach ($fields as $field_name) {
						foreach ($msg as $rule => $message) {
							$this->ownFieldMessages[$field_name][$rule] = $message;

						}
					}
				}

			}
		}

		return $this;

	}

	/**
	 * true ако има грешки
	 * @param null $field
	 * @return bool
	 */
	public function hasErrors($field = null)
	{
		if ($field === null) {
			return $this->errors->any();
		}

		return $this->errors->has($field);

	}

	/**
	 * @param $field
	 * @param $msg
	 */
	public function set_error($field, $msg)
	{
		$this->errors->{$field} = $msg;
	}

	/**
	 * @param string $field
	 * @param string $prefix
	 * @param string $postfix
	 * @param string $format
	 * @return string
	 * @throws Exception
	 */
	public function errors($field = '', $prefix = '', $postfix = '', $format = '')
	{
		if (!$prefix) {

			$prefix = $this->_prefix_tag;
		}

		if (!$postfix) {

			$postfix = $this->_postfix_tag;
		}

		if (!$format) {

			$format = $this->_alert_format;
		}

		if (empty($this->errors->get())) {
			//не искам да форматира когато няма грешки
			return;
		}

		if ($field === '') {

			foreach ($this->errors->get() as $values) {

				foreach ($values as $msg) {

					$this->error_string .= $prefix . $msg . $postfix;
				}
			}
		} else {

			if (array_key_exists($field, $this->errors->get())) {

				$msg = current($this->errors->get($field));

				$this->error_string = $prefix . $msg . $postfix;

			}
		}

		return sprintf($format, $this->error_string);
	}

	/**
	 * @return string
	 * @throws JsonException
	 */
	public function toJson(): string
	{
		return $this->errors->toJson();
	}

	/**
	 * @return MessageBag
	 */
	public function messageBag(): MessageBag
	{
		return $this->errors;
	}

	/**
	 * __call
	 *
	 * @param $a
	 * @param $b
	 * @throws Exception
	 */
	public function __call($a, $b)
	{
		throw new Exception('Missing Validation rule: ' . $a, 500);
	}

	public function __destruct()
	{
		if ($this->errors->any() === true) {
			sessionData('_old_input', $this->old);
			sessionData('_errors', $this->errors);
		}

	}
}
