<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage;

/**
 * Class AdapterInterface
 * @package SprykerFeature\Shared\Library\DataSource
 */
interface AdapterInterface
{

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return boolean
     */
    public function getDebug();

    /**
     * @param $debug
     * @return $this
     */
    public function setDebug($debug);

    /**
     * @return void
     */
    public function connect();
}
