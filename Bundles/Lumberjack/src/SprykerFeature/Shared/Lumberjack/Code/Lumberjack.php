<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Lumberjack\Code;

use SprykerFeature\Shared\Library\Application\TestEnvironment;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Lumberjack\Code\Log\Data;
use SprykerFeature\Shared\Lumberjack\Code\Log\Helper;
use SprykerFeature\Shared\Lumberjack\LumberjackConfig;
use SprykerFeature_Shared_Library_Data as DataHelper;

class Lumberjack
{

    const REFERER = 'referer';
    const HTTP_USER_AGENT = 'httpUserAgent';

    /**
     * @var Lumberjack
     */
    private static $instance;

    /**
     * @var string
     */
    public $requestId = null;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var Data
     */
    protected $data;

    /**
     * @var int
     */
    protected static $logCounter = 1;

    protected static $route = 'unknown';

    /**
     * @var int
     */
    protected static $errorCounter = 0;

    private function __construct()
    {

    }

    /**
     * @return Lumberjack|static
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        self::$instance->init();

        return self::$instance;
    }

    public static function setRoute($route = null)
    {
        if (isset($route)) {
            self::$route = $route;
        }
    }

    protected function init()
    {
        if (isset($this->data)) {
            return;
        }

        $this->data = new Data($this);
        $this->helper = new Helper();
        $this->data->setHost($this->helper->getHost());
        $this->data->setApplication(APPLICATION);
        $this->data->setEnvironment(APPLICATION_ENV);
        $this->data->setDateAndTime(gmdate('Y-m-d\TH:i:s'));
        $this->data->setIp(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        $this->data->setStore(\SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName());
        $this->data->setLanguage(\SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLanguage());
        $this->data->setLocale(\SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale());

        $url = $this->helper->getUrl();
        $this->data->setUrl($url);
        $httpReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown';
        $this->addField(self::REFERER, $httpReferer);

        if (APPLICATION === 'YVES') {
            $this->requestId = $this->helper->addVariablesForYVES($this->data, $this->requestId, self::$route);
        }

        if (APPLICATION === 'ZED') {
            $this->requestId = $this->helper->addVariablesForZED($this->data, $this->requestId);
        }

        if (false === headers_sent()) {
            header('X-LumberjackId: ' . $this->requestId);
        }

    }

    public function addHttpUserAgent()
    {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
        $this->addField(self::HTTP_USER_AGENT, $agent);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addField($key, $value)
    {
        $this->data->addField($key, $value);
    }

    /**
     * @param array $fields
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $k => $v) {
            $this->addField($k, $v);
        }
    }

    /**
     * @param string $type
     * @param string $shortMessage
     * @param null|string $subType
     */
    public function send($type, $shortMessage, $subType = null)
    {
        if (TestEnvironment::isSystemUnderTest()) {
            return;
        }

        if (self::$errorCounter > 0) {
            return;
        }

        if (is_string($shortMessage) && strpos($shortMessage, "\n") !== false) {
            $shortMessage = current(explode("\n", $shortMessage));
        }

        $this->data->setType($type);
        $this->data->setSubType($subType);
        $this->data->setMessage($shortMessage);
        $microtime = (microtime(true) * 1000);
        $this->data->setMicrotime(floor($microtime));
        // Ultratime is used to keep the sequence of events, even if they happen in the same microsecond
        $ultratime = floor($microtime * 1000 + self::$logCounter);
        $this->data->setUltratime($ultratime);
        self::$logCounter++;

        $dataArray = $this->data->getData();
        $dataArray = $this->sanitizeData($dataArray);

        try {
            $data = json_encode($dataArray);
            $path = DataHelper::getLocalCommonPath('lumberjack');

            file_put_contents($path . 'lumberjack-' . date('Y-m-d') . '.log', $data . PHP_EOL, FILE_APPEND);
        } catch (\Exception $e) {
            \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->noticeError('Lumberjack-Error: ' . $e->getMessage(), $e);
        }

        unset($this->data);
    }

    /**
     * @param mixed $data
     *
     * @return array|string
     */
    protected function sanitizeData($data)
    {
        if (!is_array($data)) {
            if (is_string($data)) {
                return $this->sanitizeString('');
            }

            return $data;
        }

// TODO needs to be reimplemented
//        $dataKeysToSanitize = $this->getDataKeysToSanitize();
//
//        foreach ($data as $key => $value) {
//            foreach ($dataKeysToSanitize->getData() as $keyNameToSanitize => $lengthAfterSanitizing) {
//                if (stristr($key, $keyNameToSanitize)) {
//                    $data[$key] = $this->sanitizeString('', $lengthAfterSanitizing);
//                }
//            }
//        }

        return $data;
    }

    /**
     * @param string $string
     * @param int $length
     * @param string $character
     *
     * @return string
     */
    protected function sanitizeString($string, $length = 5, $character = '*')
    {
        return str_pad($string, $length, $character, STR_PAD_RIGHT);
    }

    /**
     * @return mixed
     */
    protected function getDataKeysToSanitize()
    {
        // TODO Re-Code this
        return [];

        $lumberjackConfig = Config::get(LumberjackConfig::LUMBERJACK);

        if ($lumberjackConfig->offsetExists('keys_to_sanitize')) {
            return $lumberjackConfig->keys_to_sanitize;
        }
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return bool
     */
    public function flattenArrayData()
    {
        return true;
    }

}
