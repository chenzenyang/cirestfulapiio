<?php
namespace chenzenyang\Cirestfulapiio;

use chriskacerguis\RestServer\RestController;

class Cirestfulapiio extends RestController {

	public $request_data  = array();
	public $response_data = array('code' => 400, 'status' => NULL, 'message' => '');
	public $header_prefix = 'cirestfulapiio';
	public object $token_obj;

	private $another_data = array();
	private string $verbs = '';
	private Object $Simple_verb_factory;
	private Object $Verb_request;
	private Object $Simple_verify_factory;
	private Object $Field_verify;

	public function __construct()
	{
		parent::__construct();

		$this->Simple_verb_factory   = new Simple_verb_factory();
		$this->Simple_verify_factory = new Simple_verify_factory();
	}

	/**
	 * @verbs : API 動詞 (若為 BEARER 則是放在http標頭檔中的Authorization, FILE, IMG)
	 * @key_array :
	 * 		column
	 * 		0 		參數名稱(key)
	 * 	 	1 		參數預設值(若要必填,則此必須為FALSE)
	 * 		2 		若參數值(value)為空, 輸出警告文字(FALSE 則為非必填)
	 * 		3-form	value的資料型態(text、number、array、json)
	 * 		3-file	file或是img的預設值設定,若為FALSE或是NULL就用預設值
	 *  	4-form 	value的驗證型態
	 * 		4-file	是否回傳詳細訊息(丟給$this->request_data)
	 *  	5 		此欄位是否return成另一個資料, (無論是否皆會丟進$this->request_data)
	 */
	final public function request($verbs, array $key_array)
	{
		// 建立 $verbs 的簡單工廠, create 一個 verb 的物件
		if ($this->verbs != $verbs)
		{
			$this->verbs = $verbs;
			$this->Verb_request = $this->Simple_verb_factory->create_verb($verbs);
		}

		// 把陣列依序丟進verb物件做事情
		foreach ($key_array as $key => $value)
		{
			// Stept 1 : 接收 value
			$this->_receive_field($value[0], $value[5]);

			// Stept 2 : 確認欄位是否有預設值,若無則再確認是否必填
			$this->_check_field($value[0], $value[1], $value[2]);

			// Stept 3 : 驗證資料型態
			$this->_verify_type($value[0], $value[3]);

			// Stept 4 : 驗證表單欄位
			if ($value[4] != FALSE AND is_string($value[4]))
			{
				$this->_field_verify($verbs, $value[0], $value[4]);
			}
			elseif (($verbs == 'FILE' OR $verbs == 'IMG') AND $value[4] === TRUE)
			{
				$this->_field_verify($verbs, $value[0], $value[4]);
			}
		}

		return $this->another_data;
	}

	final public function response($code = NULL, $msg = NULL, $data = NULL, $continue = FALSE)
	{
		if ($msg != NULL)
			$this->response_data['message'] = $msg;

		if ($code == NULL)
			$code = $this->response_data['code'];

		unset($this->response_data['code']);

		if ( ! is_array($data))
			$data = array();

		if (empty($this->response_data['message']) OR empty($this->response_data['status']))
		{
			$msg_flag = FALSE;
			switch ($code)
			{
				case 200:
					$this->response_data['status']  = TRUE;
					$msg_flag = TRUE;
					break;

				case 201:
					$this->response_data['status']  = TRUE;
					$msg_flag = TRUE;
					break;
					
				case 304:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 400:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 401:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 403:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 404:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 405:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 406:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
				case 500:
					$this->response_data['status']  = FALSE;
					$msg_flag = TRUE;
					break;
					
					
				default:
					$data['status']  = $code;
					break;
			}

			if ($msg_flag == TRUE AND empty($this->response_data['message']))
				$this->response_data['message'] = output_msg($code);
		}

		parent::response(array_merge($this->response_data, $data), $code, $continue);
	}

	public function set_token_obj($obj)
	{
		$this->token_obj = $obj;
	}


	// ----------------------------------------------------------


	private function _receive_field($value_0, $value_5)
	{
		$field_name = $this->_set_field_name($value_0, $value_5);

		$this->request_data[$value_0] = $this->Verb_request->receive($this, $value_0);

		if (($value_5 === TRUE) OR (is_string($value_5) AND ( ! empty($value_5))))
		{
			$this->another_data[$field_name] = $this->Verb_request->receive($this, $value_0);
		}
	}

	private function _check_field($value_0, $value_1, $value_2)
	{
		if (empty($this->request_data[$value_0]) OR ( ! isset($this->request_data[$value_0])))
		{
			if ($value_1 !== FALSE)
			{
				$this->request_data[$value_0] = $value_1;
			}
			else
			{
				if (is_string($value_2) AND ( ! empty($value_2)))
				{
					if (empty($this->response_data['message']))
					{
						$this->response(400, $value_2);
					}
					else
					{
						$this->response(400);
					}
				}
			}
		}
	}

	private function _verify_type($value_0, $value_3)
	{
		$type_flag = TRUE;

		if (gettype($value_3) == 'string')
		{
			switch ($value_3)
			{
				case 'string':
					if ( ! is_string($this->request_data[$value_0]))
						$type_flag = FALSE;
					break;

				case 'number':
					if ( ! is_numeric($this->request_data[$value_0]))
						$type_flag = FALSE;
					break;

				case 'array':
					if ( ! is_array($this->request_data[$value_0]))
						$type_flag = FALSE;
					break;

				case 'json':
					if ( ! (is_string($this->request_data[$value_0]) AND
						is_array(json_decode($this->request_data[$value_0], TRUE)) AND
						((json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE)))
						$type_flag = FALSE;
					break;
			}

			// 輸入類型不正確, 則跳出警告訊息
			if ($type_flag == FALSE)
			{
				$this->response(400, $value_0 . output_msg('form_35'));
			}
		}
		elseif (gettype($value_3) == 'array')
		{
#! 尚未完成
			$do_something = TRUE;
		}
	}

	private function _field_verify($verbs, $value_0, $value_4)
	{
		if ($verbs == 'FILE' OR $verbs == 'IMG')
		{
			$this->request_data[$value_0] = $this->Verb_request->show_detail();
		}
		else
		{
			$this->Field_verify = $this->Simple_verify_factory->create_verify($value_4);
			if ( ! $this->Field_verify->verify($this->request_data[$value_0]))
			{
				$this->response(400, $value_4 . output_msg('form_35'));
			}
		}
	}

	private function _set_field_name($default_name, $is_return)
	{
		if ($is_return === TRUE)
		{
			return $default_name;
		}
		elseif (is_string($is_return) AND ( ! empty($is_return)))
		{
			return $is_return;
		}
	}
}


function output_msg($key = '0000')
{
	$msg = array(
			'0000' => '?',

			'form_01' => '新增',	
			'form_02' => '編輯',
			'form_03' => '刪除',
			'form_21' => '新增成功',
			'form_22' => '編輯成功',
			'form_25' => '編輯發生錯誤',
			'form_35' => '資料的類型不正確!',
			'form_50' => '發生錯誤',
			'form_51' => 'Insert',
			'form_52' => 'Edit',
			'form_53' => 'Delete',

			'register_01' => '註冊成功',
			'register_02' => '註冊失敗',
			'register_03' => '社群註冊失敗',

			'login_01' => '登錄成功',
			'login_02' => '查無此帳號',
			'login_03' => '密碼輸入錯誤',
			'login_04' => '您無權限登錄',
			'login_05' => '此Token已過期,請重新登入',
			'login_06' => '此帳號已被註冊',
			'login_07' => '帳號或密碼不正確',
			'login_08' => '儲存push_token時發生錯誤',

			'signup_01' => '帳號不可為空',
			'signup_02' => '密碼不可為空',
			'signup_03' => '兩次輸入的密碼不相同',

			'info_01' => '密碼變更成功',

			'msg_01' => 'success',
			'msg_02' => 'error',
			'msg_11' => '資料取得成功',
			'msg_12' => '資料取得失敗',

			'priv_02' => '此使用者無權限使用此資源',

			// For http status message used
			'200' => 'success',
			'201' => '新增成功',
			'304' => '請求的資源未被修改',
			'400' => '客戶端使用無效的請求',
			'401' => '此客戶端尚未被驗證',
			'403' => '此客戶端是被禁止使用此請求',
			'404' => '請求的資源不存在',
			'405' => '請求的方法不支援',
			'406' => '標頭檔不支援',
			'500' => '伺服器發生問題, 請聯絡工程師',
	);

	return $msg[$key];
}