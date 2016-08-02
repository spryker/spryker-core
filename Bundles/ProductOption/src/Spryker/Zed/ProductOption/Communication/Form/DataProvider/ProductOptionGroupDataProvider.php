<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductOption\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionGroupForm;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface;

class ProductOptionGroupDataProvider
{
    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    protected $taxFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected $productOptionGroupTransfer;

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     */
    public function __construct(
        ProductOptionToTaxInterface $taxFacade,
        ProductOptionGroupTransfer $productOptionGroupTransfer = null
    ) {
        $this->taxFacade = $taxFacade;
        $this->productOptionGroupTransfer = $productOptionGroupTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            ProductOptionGroupForm::OPTION_TAX_SETS => $this->createTaxSetsList(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function getData()
    {
        return $this->productOptionGroupTransfer;
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
