<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyCollectionTransfer;
use Generated\Shared\Transfer\CurrencyCriteriaTransfer;

class CurrencyGuiToCurrencyFacadeBridge implements CurrencyGuiToCurrencyFacadeInterface
{
    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Currency\Business\CurrencyFacadeInterface $currencyFacade
     */
    public function __construct($currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCurrencies(array $storeTransfers): array
    {
        return $this->currencyFacade->expandStoreTransfersWithCurrencies($storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyCriteriaTransfer $currencyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyCollectionTransfer
     */
    public function getCurrencyCollection(CurrencyCriteriaTransfer $currencyCriteriaTransfer): CurrencyCollectionTransfer
    {
        return $this->currencyFacade->getCurrencyCollection($currencyCriteriaTransfer);
    }
}
