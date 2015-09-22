<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage;

/**
 * Class AdapterInterface
 */
interface AdapterInterface
{

    /**
     * @param array $config
     *
     * @return self
     */
    public function setConfig(array $config);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return bool
     */
    public function getDebug();

    /**
     * @param $debug
     *
     * @return self
     */
    public function setDebug($debug);

    /**
     */
    public function connect();

}
