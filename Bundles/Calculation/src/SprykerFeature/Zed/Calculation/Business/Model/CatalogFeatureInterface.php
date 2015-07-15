<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model;

interface CatalogFeatureInterface
{

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getProductPriceBySku($sku);

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getProductStockBySku($sku);

    /**
     * @param string $sku
     *
     * @return string
     */
    public function getProductNameBySku($sku);

    /**
     * @param string $sku
     *
     * @return float
     */
    public function getProductTaxRateBySku($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function canLoadProductBySku($sku);

    /**
     * @param string $sku
     * @param string $optionIdentifier
     *
     * @return bool
     */
    public function canProductHaveOption($sku, $optionIdentifier);

}
