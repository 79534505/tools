<?php


/**
 * HTTP Client
 *
 * @example
 * <pre>
 * Usage:
 * 
 * Http::get($url, $params);
 * Http::post($url, $params);
 * Http::put($url, $params);
 * patch, option, head....
 *
 * or:
 *
 * Http::request('GET', $url, $params);
 * </pre>
 * 
 * @author  Joy chao <anzhengchao@gmail.com>
 * @version 1.0 2014-06-17
 */
class Http
{
    /**
     * user agent
     *
     * @var string
     */
    protected static $userAgent = 'PHP Http Client';


    /**
     * set user agent
     *
     * @param string $userAgent 
     */
    public function setUserAgent($userAgent)
    {
        self::$userAgent = $userAgent;
    }

    /**
     * 发起一个HTTP/HTTPS的请求
     * 
     * @param string $method     请求类型    GET | POST...
     * @param string $url        接口的URL
     * @param array  $params     接口参数   array('content'=>'test', 'format'=>'json');
     * @param array  $files      图片信息
     * @param arrat  $extheaders 扩展的包头信息
     * 
     * @return string
     */
    public static function request($method, $url, $params = array(), $files = [], array $extheaders = array())
    {
        if(!function_exists('curl_init')) exit('Need to open the curl extension');
        $method = strtoupper($method);
        
        $ci = curl_init();
        
        curl_setopt($ci, CURLOPT_USERAGENT, self::$userAgent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 3);
        
        $timeout = $files ? 30 : 3;
        
        curl_setopt($ci, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ci, CURLOPT_HEADER, false);
        
        $headers = $extheaders;

        switch ($method) {
            case 'PUT':
            case 'POST':
            case 'PATCH':
                $method != 'POST' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method);
                
                curl_setopt($ci, CURLOPT_POST, TRUE);

                if (!empty($params)) {
                    if(!empty($files)) {
                        foreach($files as $index => $file) {
                            $params[$index] = '@' . $file;
                        }
                        curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
                        $headers[] = 'Expect: ';
                    } else {
                        curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($params));
                    }
                }
                break;

            case 'DELETE':
            case 'GET':
            case 'OPTION':
                $method != 'GET' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method);
                
                if (!empty($params)) {
                    $url = $url . (strpos($url, '?') ? '&' : '?')
                        . (is_array($params) ? http_build_query($params) : $params);
                }

                break;
        }

        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        curl_setopt($ci, CURLOPT_URL, $url);
        if ($headers) {
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        }

        $response = curl_exec($ci);
        curl_close ($ci);

        return $response;
    }
    
    /**
     * static call 
     *
     * @param string $method request method.
     * @param array  $args   request params.
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $method = strtoupper($method);
        if (!in_array($method, ['GET','POST', 'DELETE', 'PUT', 'PATCH','HEAD', 'OPTION'])) {
            throw new RuntimeException("method $method", 400);
        }

        array_unshift($args, $method);

        return call_user_func_array(array(__CLASS__, 'request'), $args);
    }
}
