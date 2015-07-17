<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Lumberjack\Code\Log;

use SprykerFeature\Shared\Lumberjack\Code\Lumberjack;

class Data
{

    const HOST = 'host';
    const APPLICATION = 'application';
    const MICROTIME = 'microtime';
    const ULTRATIME = 'ultratime';
    const IP = 'ip';
    const DATE_AND_TIME = 'dateAndTime';
    const STORE = 'store';
    const LANGUAGE = 'language';
    const LOCALE = 'locale';
    const ENVIRONMENT = 'environment';
    const URL = 'url';
    const TYPE = 'type';
    const SUBTYPE = 'subtype';
    const MESSAGE = 'message';
    const CLASS_NAME = 'CLASS_NAME';

    const MAX_FIELD_SIZE = 51200; // bytes
    const MAX_MESSAGE_SIZE = 256000; // bytes
    const MESSAGE_FIELD_IS_TOO_LONG = 'Field data was too long!';

    /**
     * @var Lumberjack
     */
    protected $lumberjack;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $overallMessageSize = 0;

    /**
     * @param Lumberjack $lumberjack
     */
    public function __construct(Lumberjack $lumberjack = null)
    {
        if (!is_null($lumberjack)) {
            $this->lumberjack = $lumberjack;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addField($key, $value)
    {
        if (is_null($value)) {
            $string = (string) null;
            $this->addDataIfPossible($key, $string);

            return;
        }

        if (is_bool($value)) {
            $string = ($value) ? '(bool) true' : '(bool) false';
            $this->addDataIfPossible($key, $string);

            return;
        }

        if (is_scalar($value)) {
            $string = (string) $value;
            /*
             * this is used to make sure only valid json (and not json within json) will be send to the elastic
             * search server.
             */
            if ($this->isJson($value)) {
                $value = addslashes($value);
            }
            $this->addDataIfPossible($key, $string);

            return;
        }

        if (is_object($value) && !($value instanceof \Traversable)) {
            $value = $this->objectToArray($value);
        }

        // here we have either array or Traversable
        if ($this->lumberjack->flattenArrayData()) {
            foreach ($this->flattenArray($key, $value) as $key => $string) {
                $this->addDataIfPossible($key, $string);
            }
        } else {
            if ($value instanceof \Traversable) {
                $this->addDataFromTraversable($value);
            } else {
                $this->addDataArrayIfPossible($key, $value);
            }
        }

    }

    /**
     * @param string $key
     * @param string $string
     */
    protected function addDataIfPossible($key, $string)
    {
        $len = strlen($string);

        if (($this->overallMessageSize + $len) <= self::MAX_MESSAGE_SIZE) {
            $this->data[$key] = $this->getCheckedString($string);
            $this->overallMessageSize += $len;
        }
    }

    /**
     * @param $key
     * @param array $data
     */
    protected function addDataArrayIfPossible($key, array $data)
    {
        foreach ($data as $key => $value) {
            if (!is_bool($value) && !is_scalar($value) && !is_array($value) && !($value instanceof \Traversable)) {
                unset($data[$key]);
            }
        }

        $lengthOfJson = strlen(json_encode($data));

        if (($this->overallMessageSize + $lengthOfJson) <= self::MAX_MESSAGE_SIZE) {
            if ($lengthOfJson > self::MAX_FIELD_SIZE) {
                $this->data[$this->filterKeyName($key)] = self::MESSAGE_FIELD_IS_TOO_LONG;
                $this->overallMessageSize += strlen(self::MESSAGE_FIELD_IS_TOO_LONG);
            } else {
                $this->data[$this->filterKeyName($key)] = $data;
                $this->overallMessageSize += $lengthOfJson;
            }
        }
    }

    /**
     * @param \Traversable $traversable
     */
    protected function addDataFromTraversable(\Traversable $traversable)
    {
        foreach ($traversable as $key => $value) {
            $this->addField($key, $value);
        }
    }

    /**
     * @param $string
     *
     * @return string
     */
    protected function getCheckedString($string)
    {
        if (strlen($string) > self::MAX_FIELD_SIZE) {
            return self::MESSAGE_FIELD_IS_TOO_LONG;
        } else {
            return $string;
        }
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function objectToArray($data)
    {
        $converted = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $converted[$key] = $this->objectToArray($value);
            }

            return $converted;
        }

        if (!is_object($data)) {
            return $data;
        }

        $converted[self::CLASS_NAME] = get_class($data);
        $array = (array) $data;
        foreach ($array as $key => $value) {
            // Added str_replace in order to get rid of the \u0000 NULL characters in front of class attribute names
            $converted[$this->filterKeyName($key)] = $this->objectToArray($value);
        }

        return $converted;
    }

    /**
     * @param string $key
     * @param \Traversable|array $data
     * @param bool $toString
     *
     * @return array
     */
    protected function flattenArray($key, $data, $toString = true)
    {
        $flattenedArray = [];
        foreach ($data as $dataKey => $value) {
            $keyName = $key . '.' . $dataKey;

            if (is_bool($value)) {
                $flattenedArray[$this->filterKeyName($keyName)] = ($value) ? '(bool) true' : '(bool) false';
            } elseif (is_scalar($value)) {

                /*
                 * this is used to make sure only valid json (and not json within json) will be send to the elastic
                 * search server.
                 */
                if ($this->isJson($value)) {
                    $value = addslashes($value);
                }

                if ($toString) {
                    $flattenedArray[$this->filterKeyName($keyName)] = (string) $value;
                } else {
                    $flattenedArray[$this->filterKeyName($keyName)] = $value;
                }
            } elseif (is_array($value) || $value instanceof \Traversable) {
                foreach ($this->flattenArray($keyName, $value) as $subDataKey => $subValue) {
                    if ($toString) {
                        $flattenedArray[$this->filterKeyName($subDataKey)] = (string) $subValue;
                    } else {
                        $flattenedArray[$this->filterKeyName($subDataKey)] = $subValue;
                    }
                }
            }
        }

        return $flattenedArray;
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    protected function isJson($string)
    {
        return json_decode($string);
    }

    /**
     * @param $keyName
     *
     * @return mixed
     */
    protected function filterKeyName($keyName)
    {
        return str_replace(['\\u0000', "\0", '*'], '', $keyName);
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->data[self::ENVIRONMENT] = $environment;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->data[self::URL] = $url;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->data[self::MESSAGE] = $message;
    }

    /**
     * @param string $subtype
     */
    public function setSubType($subtype)
    {
        $this->data[self::SUBTYPE] = $subtype;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->data[self::TYPE] = $type;
    }

    /**
     * @param string $application
     */
    public function setApplication($application)
    {
        $this->data[self::APPLICATION] = $application;
    }

    /**
     * @param string $dateAndTime
     */
    public function setDateAndTime($dateAndTime)
    {
        $this->data[self::DATE_AND_TIME] = $dateAndTime;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->data[self::HOST] = $host;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->data[self::IP] = $ip;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->data[self::LANGUAGE] = $language;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->data[self::LOCALE] = $locale;
    }

    /**
     * @param int $microtime
     */
    public function setMicrotime($microtime)
    {
        $this->data[self::MICROTIME] = $microtime;
    }

    /**
     * @param int $ultratime
     */
    public function setUltratime($ultratime)
    {
        $this->data[self::ULTRATIME] = $ultratime;
    }

    /**
     * @param string $store
     */
    public function setStore($store)
    {
        $this->data[self::STORE] = $store;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
