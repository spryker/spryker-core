<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\KeyBuilder;

use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderTrait;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class KvMarkerKeyBuilder implements KeyBuilderInterface
{

    use KeyBuilderTrait;
    /**
     * @param string $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return $data . $this->keySeparator . 'timestamp';
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'kv-export';
    }

}
