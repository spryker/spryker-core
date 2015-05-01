<?php

namespace SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;

class ProductKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param array $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'sku.' . $data;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'product';
    }
}
