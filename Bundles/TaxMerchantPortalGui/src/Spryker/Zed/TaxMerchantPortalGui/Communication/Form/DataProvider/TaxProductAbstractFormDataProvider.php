<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxMerchantPortalGui\Communication\Form\DataProvider;

use Spryker\Zed\TaxMerchantPortalGui\Dependency\Facade\TaxMerchantPortalGuiToTaxFacadeInterface;

class TaxProductAbstractFormDataProvider implements TaxProductAbstractFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\TaxMerchantPortalGui\Dependency\Facade\TaxMerchantPortalGuiToTaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\TaxMerchantPortalGui\Dependency\Facade\TaxMerchantPortalGuiToTaxFacadeInterface $taxFacade
     */
    public function __construct(TaxMerchantPortalGuiToTaxFacadeInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @return int[]
     */
    public function getTaxChoices(): array
    {
        $taxChoices = [];
        $taxSetTransfers = $this->taxFacade->getTaxSets()->getTaxSets();

        foreach ($taxSetTransfers as $taxSetTransfer) {
            /** @var int $idTaxSet */
            $idTaxSet = $taxSetTransfer->requireIdTaxSet()->getIdTaxSet();
            /** @var string $name */
            $name = $taxSetTransfer->requireName()->getName();

            $taxChoices[$name] = $idTaxSet;
        }

        return $taxChoices;
    }
}
