<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model;

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
