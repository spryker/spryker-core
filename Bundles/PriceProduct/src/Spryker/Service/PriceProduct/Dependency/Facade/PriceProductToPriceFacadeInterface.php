<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Dependency\Facade;

interface PriceProductToPriceFacadeInterface
{
    /**
     * @return string
     */
    public function getNetPriceModeIdentifier();

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier();

    /**
     * @return string
     */
    public function getDefaultPriceMode();
}
