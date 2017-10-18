<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\PriceFilterTransfer;

interface PriceCartToPriceProductInterface
{

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null);

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null);

    /**
     * @return string
     */
    public function getDefaultPriceTypeName();

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceFilterTransfer $priceFilterTransfer);

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceFilterTransfer $priceFilterTransfer);

}
