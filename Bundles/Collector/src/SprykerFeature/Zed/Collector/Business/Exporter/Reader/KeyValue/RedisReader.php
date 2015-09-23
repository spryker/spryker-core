<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Reader\KeyValue;

use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class RedisReader implements ReaderInterface
{

    const READER_NAME = 'redis-reader';

    /**
     * @var ReadInterface
     */
    protected $redis;

    /**
     * @param ReadInterface $redis
     */
    public function __construct(ReadInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return string
     */
    public function read($key, $type = '')
    {
        return $this->redis->get($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::READER_NAME;
    }

}
