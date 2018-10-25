<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Dependency\Client;

interface CatalogSearchRestApiToPriceClientInterface
{
    /**
     * @return string
     */
    public function getCurrentPriceMode();

    /**
     * @param string $priceMode
     *
     * @return void
     */
    public function switchPriceMode(string $priceMode): void;

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier();

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier();

    /**
     * @return string[]
     */
    public function getPriceModes();
}
