<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use DateTime;
use DateTimeZone;
use Exception;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\Exception\UnknownContextPropertyException;

class Context
{

    const CONTEXT_SHARED = '*';
    const CONTEXT_YVES = 'yves';
    const CONTEXT_ZED = 'zed';

    /**
     * @var array|null
     */
    protected static $contexts = null;

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @var string
     */
    protected static $defaultContext = self::CONTEXT_SHARED;

    /**
     * @var string|null
     */
    protected $_contextName = null;

    /**
     * Creates Context object based on given context name.
     *
     * @param string|\Spryker\Shared\Library\Context|null $context
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Library\Context
     */
    public static function getInstance($context = null)
    {
        if ($context instanceof self) {
            return $context;
        }
        if (empty($context)) {
            $context = static::$defaultContext;
        }
        static::loadContexts();
        if (!isset(static::$contexts[$context])) {
            throw new Exception('Incorrect context: ' . $context);
        }
        if (!isset(static::$instances[$context])) {
            static::$instances[$context] = new self($context);
        }

        return static::$instances[$context];
    }

    /**
     * Sets default context, should be used only while bootstrapping the system
     *
     * @param string|\Spryker\Shared\Library\Context $context
     *
     * @return void
     */
    public static function setDefaultContext($context = self::CONTEXT_SHARED)
    {
        if ($context instanceof self) {
            static::$defaultContext = $context->_contextName;
        } else {
            static::$defaultContext = $context;
        }
    }

    /**
     * @return string
     */
    public static function getDefaultContext()
    {
        return static::$defaultContext;
    }

    /**
     * @param string $contextName
     */
    protected function __construct($contextName)
    {
        $this->_contextName = $contextName;
    }

    /**
     * Loads and builds available contexts.
     *
     * @return void
     */
    protected static function loadContexts()
    {
        if (static::$contexts === null) {
            $contexts = Store::getInstance()->getContexts();
            if (isset($contexts['*'])) {
                $defaults = is_array($contexts['*']) ? $contexts['*'] : [];
            } else {
                $defaults = [];
            }
            foreach ($contexts as $k => $v) {
                $contexts[$k] = array_replace_recursive($defaults, $v);
            }
            static::$contexts = $contexts;
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Checks if value exists in context.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset(static::$contexts[$this->_contextName][$name]);
    }

    /**
     * Retrieves value from context.
     *
     * @param string $name
     *
     * @throws \Spryker\Shared\Library\Exception\UnknownContextPropertyException
     *
     * @return mixed
     */
    public function get($name)
    {
        if (!array_key_exists($name, static::$contexts[$this->_contextName])) {
            throw new UnknownContextPropertyException(sprintf(
                'Unknown context property "%s"',
                $name
            ));
        }

        return static::$contexts[$this->_contextName][$name];
    }

    /**
     * Converts date/time from internal timezone to context's timezone.
     *
     * @param string|\DateTime $dateTime date/time to be converted
     * @param string $format output format
     *
     * @return string
     */
    public function dateTimeConvertTo($dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!($dateTime instanceof DateTime)) {
            $dateTime = new DateTime($dateTime, new DateTimeZone(Store::getInstance()->getTimezone()));
        }
        $dateTime->setTimezone(new DateTimeZone($this->timezone));

        return $dateTime->format($format);
    }

    /**
     * Converts date/time from context's timezone to internal timezone.
     *
     * @param string|\DateTime $dateTime date/time to be converted
     * @param string $format output format
     *
     * @return string
     */
    public function dateTimeConvertFrom($dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!($dateTime instanceof DateTime)) {
            $dateTime = new DateTime($dateTime, new DateTimeZone($this->timezone));
        }
        $dateTime->setTimezone(new DateTimeZone(Store::getInstance()->getTimezone()));

        return $dateTime->format($format);
    }

    /**
     * Converts date/time to context't timezone from external context's timezone.
     *
     * @param \Spryker\Shared\Library\Context|string $contextFrom context from which to convert
     * @param string|\DateTime $dateTime date/time to be converted
     * @param string $format output format
     *
     * @return string
     */
    public function dateTimeConvertToFrom($contextFrom, $dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!($dateTime instanceof DateTime)) {
            $dateTime = new DateTime($dateTime, new DateTimeZone(self::getInstance($contextFrom)->timezone));
        }
        $dateTime->setTimezone(new DateTimeZone($this->timezone));

        return $dateTime->format($format);
    }

    /**
     * Converts date/time from context's timezone to external context't timezone.
     *
     * @param \Spryker\Shared\Library\Context|string $contextTo context to which to convert
     * @param string|\DateTime $dateTime date/time to be converted
     * @param string $format output format
     *
     * @return string
     */
    public function dateTimeConvertFromTo($contextTo, $dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!($dateTime instanceof DateTime)) {
            $dateTime = new DateTime($dateTime, new DateTimeZone($this->timezone));
        }
        $dateTime->setTimezone(new DateTimeZone(self::getInstance($contextTo)->timezone));

        return $dateTime->format($format);
    }

}
