<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface FormDataProviderInterface
{
    /**
     * @param int $idMerchantRelationship
     * @param array $defaultData
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array
     */
    public function getData(
        int $idMerchantRelationship,
        array $defaultData,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array;

    /**
     * @return array
     */
    public function getOptions();
}
