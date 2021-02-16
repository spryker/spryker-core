<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxMerchantPortalGui\Communication\Form\DataProvider;

interface TaxProductAbstractFormDataProviderInterface
{
    /**
     * @return int[]
     */
    public function getTaxChoices(): array;
}
