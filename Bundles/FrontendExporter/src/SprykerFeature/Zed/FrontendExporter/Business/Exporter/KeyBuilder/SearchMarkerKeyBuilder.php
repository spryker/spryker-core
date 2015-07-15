<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class SearchMarkerKeyBuilder implements KeyBuilderInterface
{

    use KeyBuilderTrait;

    /**
     * @param mixed $data
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
        return 'search-export';
    }

}
