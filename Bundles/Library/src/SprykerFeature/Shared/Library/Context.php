<?php

namespace SprykerFeature\Shared\Library;

use SprykerEngine\Shared\Kernel\Store;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class Context
{
    const CONTEXT_SHARED = '*';
    const CONTEXT_YVES = 'yves';
    const CONTEXT_ZED = 'zed';

    /** @var null|array */
    protected static $contexts = null;

    /** @var array */
    protected static $instances = [];

    /** @var string */
    protected static $defaultContext = self::CONTEXT_SHARED;

    /** @var null|string */
    protected $_contextName = null;

    /**
     * Creates Context object based on given context name.
     *
     * @param string|Context $context
     *
     * @throws \Exception
     *
     * @return Context
     */
    public static function getInstance($context = null)
    {
        if ($context instanceof Context) {
            return $context;
        }
        if (empty($context)) {
            $context = static::$defaultContext;
        }
        static::loadContexts();
        if (!isset(static::$contexts[$context])) {
            throw new \Exception('Incorrect context: ' . $context);
        }
        if (!isset(static::$instances[$context])) {
            static::$instances[$context] = new self($context);
        }

        return static::$instances[$context];
    }

    /**
     * Sets default context, should be used only while bootstrapping the system
     *
     * @param string|Context $context
     */
    public static function setDefaultContext($context = self::CONTEXT_SHARED)
    {
        if ($context instanceof Context) {
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
     */
    protected static function loadContexts()
    {
        if (null === static::$contexts) {
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
     * @return mixed
     */
    public function get($name)
    {
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
        if (!($dateTime instanceof \DateTime)) {
            $dateTime = new \DateTime($dateTime, new \DateTimeZone(Store::getInstance()->getTimezone()));
        }
        $dateTime->setTimezone(new \DateTimeZone($this->timezone));

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
        if (!($dateTime instanceof \DateTime)) {
            $dateTime = new \DateTime($dateTime, new \DateTimeZone($this->timezone));
        }
        $dateTime->setTimezone(new \DateTimeZone(Store::getInstance()->getTimezone()));

        return $dateTime->format($format);
    }

    /**
     * Converts date/time to context't timezone from external context's timezone.
     *
     * @param Context|string $contextFrom context from which to convert
     * @param string|\DateTime $dateTime date/time to be converted
     * @param string $format output format
     *
     * @return string
     */
    public function dateTimeConverToFrom($contextFrom, $dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!($dateTime instanceof \DateTime)) {
            $dateTime = new \DateTime($dateTime, new \DateTimeZone(self::getInstance($contextFrom)->timezone));
        }
        $dateTime->setTimezone(new \DateTimeZone($this->timezone));

        return $dateTime->format($format);
    }

    /**
     * Converts date/time from context's timezone to external context't timezone.
     *
     * @param Context|string $contextTo context to which to convert
     * @param string|\DateTime $dateTime date/time to be converted
     * @param string $format output format
     *
     * @return string
     */
    public function dateTimeConvertFromTo($contextTo, $dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!($dateTime instanceof \DateTime)) {
            $dateTime = new \DateTime($dateTime, new \DateTimeZone($this->timezone));
        }
        $dateTime->setTimezone(new \DateTimeZone(self::getInstance($contextTo)->timezone));

        return $dateTime->format($format);
    }

}
