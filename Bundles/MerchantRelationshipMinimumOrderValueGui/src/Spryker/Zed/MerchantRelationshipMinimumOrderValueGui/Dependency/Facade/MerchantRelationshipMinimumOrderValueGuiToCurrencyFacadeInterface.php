<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade;

interface MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface
{
    /**
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer[]
     */
    public function getAllStoresWithCurrencies();

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent();

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode);
}
