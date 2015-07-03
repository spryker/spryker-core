<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\KvStorage\Service\Redis;

use SprykerFeature\Client\KvStorage\Service\KvStorageClientInterface;

interface ServiceInterface extends KvStorageClientInterface
{

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config);

    /**
     * @param $debug
     * @return $this
     */
    public function setDebug($debug);

    /**
     * @throws \MemcachedException
     */
    public function connect();

}
