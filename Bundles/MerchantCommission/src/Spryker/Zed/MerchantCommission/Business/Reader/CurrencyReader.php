<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\CurrencyCollectionTransfer;
use Generated\Shared\Transfer\CurrencyConditionsTransfer;
use Generated\Shared\Transfer\CurrencyCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToCurrencyFacadeInterface;

class CurrencyReader implements CurrencyReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToCurrencyFacadeInterface
     */
    protected MerchantCommissionToCurrencyFacadeInterface $currencyFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(MerchantCommissionToCurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param list<string> $currencyCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyCollectionTransfer
     */
    public function getCurrencyCollectionByCodes(array $currencyCodes): CurrencyCollectionTransfer
    {
        $currencyConditionsTransfer = (new CurrencyConditionsTransfer())->setCodes($currencyCodes);
        $currencyCriteriaTransfer = (new CurrencyCriteriaTransfer())->setCurrencyConditions($currencyConditionsTransfer);

        return $this->currencyFacade->getCurrencyCollection($currencyCriteriaTransfer);
    }
}
