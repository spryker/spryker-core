<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

/**
 * @property \CouchBase $resource
 *
 * @method \Couchbase getResource()
 */
abstract class Couchbase extends AbstractKeyValue
{

    const BUCKET_NAME_POSTFIX = 'yves';

    /**
     * @throws \CouchbaseException
     */
    public function connect()
    {
        if (!$this->resource) {

            $hosts = $this->getCouchebaseHosts();

            if (!is_array($hosts)) {
                throw new \Exception('Please configure the couchbase cluster properly!');
            }

            $resource = new \Couchbase(
                $hosts,
                isset($this->config['user']) ? $this->config['user'] : '',
                isset($this->config['password']) ? $this->config['password'] : '',
                isset($this->config['bucket']) && !empty($this->config['bucket']) ? $this->config['bucket'] : $this->getBucketName()
            );
            $resource->setOption(COUCHBASE_OPT_SERIALIZER, COUCHBASE_SERIALIZER_JSON_ARRAY);

            if (isset($this->config['timeout'])) {
                $resource->setTimeout($this->config['timeout']);
            }

            $this->resource = $resource;
        }
    }

    /**
     * @return array
     */
    protected function getCouchebaseHosts()
    {
        $hostsWithPorts = [];
        foreach ($this->config['hosts'] as $host) {
            $hostsWithPorts[] = $host['host'] . ':' . $host['port'];
        }

        return $hostsWithPorts;
    }

    /**
     * @return string
     */
    protected function getBucketName()
    {
        $storeName = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();
        $environment = \SprykerFeature\Shared\Library\Environment::getInstance()->getEnvironment();

        return $storeName . '_' . $environment . '_' . self::BUCKET_NAME_POSTFIX;
    }

}
