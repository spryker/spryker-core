<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductOption\Communication\Form\DataProvider;

use Spryker\Zed\ProductOption\Communication\Form\GeneralForm;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface;

class GeneralFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    public function __construct(ProductOptionToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            GeneralForm::OPTION_TAX_SETS => $this->createTaxSetsList(),
        ];
    }

    /**
     * @param int|null $idMethod
     *
     * @return array
     */
    public function getData($idMethod = null)
    {
        return [];
    }

    /**
     * @return array
     */
    protected function createTaxSetsList()
    {
        $taxSetCollection = $this->taxFacade->getTaxSets();
        if (!$taxSetCollection) {
            return [];
        }

        $taxSetList = [];
        foreach ($taxSetCollection->getTaxSets() as $taxSetTransfer) {
            $taxSetList[$taxSetTransfer->getIdTaxSet()] = $taxSetTransfer->getName();
        }

        return $taxSetList;
    }
}
