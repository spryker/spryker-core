<?php

namespace SprykerFeature\Shared\Lumberjack\Code\Log;

use SprykerFeature\Shared\Library\System;
use Symfony\Component\HttpFoundation\Request;

class Helper
{

    const YVES_SESSION_ID = 'sessionIdYves';
    const ZED_REQUEST_ID = 'requestIdZed';
    const YVES_REQUEST_ID = 'requestIdYves';

    /**
     * @var string
     */
    protected static $requestId;

    /**
     * @return string
     */
    public function getUrl()
    {
        $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'unknown';
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $protocol = 'http://';

        if ($this->isSecureConnection()) {
            $protocol = 'https://';
        }
        $url = $protocol . $serverName . $requestUri;
        return $url;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return isset($_SERVER['COMPUTERNAME']) ? $_SERVER['COMPUTERNAME'] : System::getHostname();
    }

    /**
     * Refactor: this should not be here
     * @return bool
     */
    protected function isSecureConnection()
    {
        if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param Data $data
     * @param string $key
     * @param $array
     */
    public function transformArray(Data $data, $key, $array)
    {
        foreach ($array as $k => $v) {
            $keyName = $key . '.' . $k;
            if (is_array($v)) {
                $this->transformArray($data, $keyName, $v);
            } else {
                $data->addField($keyName, $v);
            }
        }
    }

    /**
     * @param Data $data
     * @param null $requestId
     * @return null|string
     */
    public function addVariablesForZED(Data $data, $requestId = null)
    {
        $yvesRequestId = null;
        $request = Request::createFromGlobals();
        if (isset($request)) {
            $route = $request->attributes->get('module') . '/' . $request->attributes->get('controller') . '/' . $request->attributes->get('action');
            $yvesRequestId = $request->query->get('yvesRequestId');
        } else {
            $route = 'unknown';
        }

        if (is_null($requestId)) {
            $requestId = 'z' . md5($route . microtime());
        }

        if (isset($yvesRequestId)) {
            $data->addField(self::YVES_REQUEST_ID, $yvesRequestId);
        }

        $data->addField('route', $route);
        $data->addField(self::ZED_REQUEST_ID, $requestId);

        return $requestId;
    }

    /**
     * @param Data $data
     * @param null $requestId
     * @param $route
     * @return string
     */
    public function addVariablesForYVES(Data $data, $requestId = null, $route)
    {
        if (is_null($requestId)) {
            $requestId = 'y' . md5(rand() . microtime());
        }
        $data->addField(self::YVES_SESSION_ID, session_id());
        $data->addField(self::YVES_REQUEST_ID, $requestId);

        $data->addField('route', $route);

        return $requestId;
    }
}
