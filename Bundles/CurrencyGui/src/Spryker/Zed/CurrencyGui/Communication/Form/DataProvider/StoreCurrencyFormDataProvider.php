<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyCriteriaTransfer;
use Spryker\Zed\CurrencyGui\Communication\Form\StoreCurrencyForm;
use Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToCurrencyFacadeInterface;

class StoreCurrencyFormDataProvider
{
    /**
     * @var \Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToCurrencyFacadeInterface
     */
    protected CurrencyGuiToCurrencyFacadeInterface $currencyFacade;

    /**
     * @param \Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(CurrencyGuiToCurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        return [
            StoreCurrencyForm::CURRENCY_OPTIONS => $this->getCurrencyOptions(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getCurrencyOptions(): array
    {
        $currencyOptions = [];
        foreach ($this->currencyFacade->getCurrencyCollection(new CurrencyCriteriaTransfer())->getCurrencies() as $currencyTransfer) {
            $currencyOptions[$currencyTransfer->getNameOrFail()] = $currencyTransfer->getCodeOrFail();
        }

        return $currencyOptions;
    }
}
