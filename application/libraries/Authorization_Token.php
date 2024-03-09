<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authorization_Token
 * ----------------------------------------------------------
 * API Token Generate/Validation
 * 
 * @author: Jeevan Lal
 * @version: 0.0.1
 */

require_once APPPATH . 'third_party/php-jwt/JWT.php';
require_once APPPATH . 'third_party/php-jwt/BeforeValidException.php';
require_once APPPATH . 'third_party/php-jwt/ExpiredException.php';
require_once APPPATH . 'third_party/php-jwt/SignatureInvalidException.php';

use \Firebase\JWT\JWT;
#[\AllowDynamicProperties]

class Authorization_Token 
{
    /**
     * Token Key
     */
    protected $token_key;

    /**
     * Token algorithm
     */
    protected $token_algorithm;

    /**
     * Token Request Header Name
     */
    protected $token_header;

    /**
     * Token Expire Time
     */
    protected $token_expire_time; 


    public function __construct()
	{
        $this->CI =& get_instance();

        /** 
         * jwt config file load
         */
        $this->CI->load->config('jwt');

        /**
         * Load Config Items Values 
         */
        $this->token_key        = $this->CI->config->item('jwt_key');
        $this->token_algorithm  = $this->CI->config->item('jwt_algorithm');
        $this->token_header  = $this->CI->config->item('token_header');
        $this->token_expire_time  = $this->CI->config->item('token_expire_time');
    }

    /**
     * Generate Token
     * @param: {array} data
     */
    public function generateToken($data = null)
    {
        if ($data AND is_array($data))
        {
            // add api time key in user array()
            //$data['API_TIME'] = time();
            try {
                return JWT::encode($data, $this->token_key, $this->token_algorithm);
            }
            catch(Exception $e) {
                return 'Message: ' .$e->getMessage();
            }
        } else {
            return "Token Data Undefined!";
        }
    }

    /**
     * Validate Token with Header
     * @return : user informations
     */
    public function validateToken()
    {
        /**
         * Request All Headers
         */
        $headers = $this->CI->input->request_headers();
        $token_data = $this->tokenIsExist($headers);
        $user_token = '';
        /**
             * Token Decode
             */
            try {
                if($token_data['status']) {
                    $token_values = explode("Bearer ", $token_data['token']);
                    $user_token = $token_values[1];
                    if($user_token !== '' && $user_token !== null) {
                        $token_decode = JWT::decode($user_token, $this->token_key, array($this->token_algorithm));
                        $user_id = $token_decode->user_id;
                        return ['status' => TRUE, 'user_id' => $user_id,  'message' => 'Token verified successfully.'];
                    }
                    else {
                        //return error
                    }
                }
                else {
                    //return error
                }
            }
            catch(Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
    }

    /**
     * Token Header Check
     * @param: request headers
     */
    private function tokenIsExist($headers)
    {
        if(!empty($headers) AND is_array($headers)) {
            foreach ($headers as $header_name => $header_value) {
                if (strtolower(trim($header_name)) == strtolower(trim($this->token_header)))
                    return ['status' => TRUE, 'token' => $header_value];
            }
        }
        return ['status' => FALSE, 'message' => 'Token is not defined.'];
    }
}