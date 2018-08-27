<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Spryker\Zed\MinimumOrderValueGui\Communication\Form\SettingsForm;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToTaxFacadeInterface;

class SettingsFormDataProvider
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToTaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToTaxFacadeInterface $taxFacade
     */
    public function __construct(MinimumOrderValueGuiToTaxFacadeInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options[SettingsForm::OPTION_TAX_SETS] = $this->createTaxSetsList();

        return $options;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return ['fkTaxSet' => 6];
    }

    /**
     * @return array
     */
    protected function createTaxSetsList(): array
    {
        $taxSetCollection = $this->taxFacade->getTaxSets();
        if (!$taxSetCollection) {
            return [];
        }

        $taxSetList = [];
        foreach ($taxSetCollection->getTaxSets() as $taxSetTransfer) {
            $taxSetList[$taxSetTransfer->getName()] = $taxSetTransfer->getIdTaxSet();
        }

        return $taxSetList;
    }
}
