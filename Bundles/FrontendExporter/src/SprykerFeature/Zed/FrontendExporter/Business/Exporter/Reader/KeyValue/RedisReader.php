<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter\Reader\KeyValue;

use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Reader\ReaderInterface;

class RedisReader implements ReaderInterface
{

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
     *
     * @return mixed
     */
    public function read($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'redis-reader';
    }

}
