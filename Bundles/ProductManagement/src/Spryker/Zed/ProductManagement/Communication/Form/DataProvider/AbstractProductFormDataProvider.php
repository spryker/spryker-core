<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;

class AbstractProductFormDataProvider
{

    const LOCALE_NAME = 'locale_name';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;


    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToLocaleInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->productQueryContainer = $productQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->locale = $localeFacade->getCurrentLocale();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $formOptions = [

        ];

        return $formOptions;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributes($idProductAbstract)
    {
        $attributeCollection = $this->productQueryContainer
            ->queryProductAbstractAttributes($idProductAbstract)
            ->innerJoinLocale()
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, self::LOCALE_NAME)
            ->find();

        $localizedAttributes = [];
        foreach ($attributeCollection as $attribute) {
            $data = $attribute->toArray();
            $localizedAttributes[$data[self::LOCALE_NAME]] = $data;
        }

        return $localizedAttributes;
    }

    /**
     * @return array
     */
    public function getAttributesDefaultFields()
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        $fields = [];
        foreach ($availableLocales as $id => $code) {
            $fields[$code] = [
                ProductFormAdd::FIELD_NAME => null,
                ProductFormAdd::FIELD_DESCRIPTION => null,
            ];
        }

        return $fields;
    }

}
