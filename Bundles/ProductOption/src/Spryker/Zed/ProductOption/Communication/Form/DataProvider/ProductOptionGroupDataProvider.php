<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductOption\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionGroupForm;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface;

class ProductOptionGroupDataProvider
{

    const NEW_GROUP_NAME = 'new_group_name';

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    protected $taxFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected $productOptionGroupTransfer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface $localeFacade
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer|null $productOptionGroupTransfer
     */
    public function __construct(
        ProductOptionToTaxInterface $taxFacade,
        ProductOptionToLocaleInterface $localeFacade,
        ProductOptionGroupTransfer $productOptionGroupTransfer = null
    ) {
        $this->taxFacade = $taxFacade;
        $this->productOptionGroupTransfer = $productOptionGroupTransfer;
        $this->localeFacade = $localeFacade;
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
        if (!$this->productOptionGroupTransfer) {
            return $this->getDefaultProductOptionGroupTransfer();
        }

        if ($this->productOptionGroupTransfer->getGroupNameTranslations()->count() === 0) {
            return $this->addDefaultGroupNameTranslations($this->productOptionGroupTransfer);
        }

        return $this->productOptionGroupTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function getDefaultProductOptionGroupTransfer()
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();

        $this->addDefaultGroupNameTranslations($productOptionGroupTransfer);

        return $productOptionGroupTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function addDefaultGroupNameTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();
        foreach ($availableLocales as $localeTransfer) {
            $productOptionGroupNameTranslationTransfer = new ProductOptionTranslationTransfer();
            $productOptionGroupNameTranslationTransfer->setLocaleCode($localeTransfer->getLocaleName());
            $productOptionGroupNameTranslationTransfer->setRelatedOptionHash(self::NEW_GROUP_NAME);
            $productOptionGroupTransfer->addGroupNameTranslation($productOptionGroupNameTranslationTransfer);
        }

        return $productOptionGroupTransfer;
    }

}
