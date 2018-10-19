<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\SettingsForm;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToTaxFacadeInterface;

class SettingsFormDataProvider
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToTaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToTaxFacadeInterface $taxFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     */
    public function __construct(
        SalesOrderThresholdGuiToTaxFacadeInterface $taxFacade,
        SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
    ) {
        $this->taxFacade = $taxFacade;
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = [];

        $options[SettingsForm::OPTION_TAX_SETS] = $this->createTaxSetsList();

        return $options;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [SettingsForm::FIELD_TAX_SET => $this->salesOrderThresholdFacade->findSalesOrderThresholdTaxSetId()];
    }

    /**
     * @return array
     */
    protected function createTaxSetsList(): array
    {
        $taxSetCollection = $this->taxFacade->getTaxSets();
        if (empty($taxSetCollection)) {
            return [];
        }

        $taxSetList = [];
        foreach ($taxSetCollection->getTaxSets() as $taxSetTransfer) {
            $taxSetList[$taxSetTransfer->getName()] = $taxSetTransfer->getIdTaxSet();
        }

        return $taxSetList;
    }
}
