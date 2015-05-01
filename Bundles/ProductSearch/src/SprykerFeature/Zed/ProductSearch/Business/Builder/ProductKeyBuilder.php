<?php

namespace SprykerFeature\Zed\ProductSearch\Business\Builder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class ProductKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param string $data
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
        return 'product-search';
    }
}
