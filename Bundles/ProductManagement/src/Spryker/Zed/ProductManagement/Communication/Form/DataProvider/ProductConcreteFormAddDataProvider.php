<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Everon\Component\Collection\Collection;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormAdd;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\ProductManagement\ProductManagementConfig;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class ProductConcreteFormAddDataProvider extends AbstractProductFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface
     */
    protected $productStockHelper;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface
     */
    protected $productAttributeFacade;

    /**
     * @paran ProductManagementToProductAttributeInterface $productAttributeFacade
     *
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocale
     * @param array $attributeCollection
     * @param array $taxCollection
     * @param string $imageUrlPrefix
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface $store
     * @param \Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface $productStockHelper
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface $productAttributeFacade
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductQueryContainerInterface $productQueryContainer,
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToProductImageInterface $productImageFacade,
        LocaleProvider $localeProvider,
        LocaleTransfer $currentLocale,
        array $attributeCollection,
        array $taxCollection,
        $imageUrlPrefix,
        ProductManagementToStoreInterface $store,
        ProductStockHelperInterface $productStockHelper,
        ProductManagementToProductAttributeInterface $productAttributeFacade
    ) {
        parent::__construct(
            $categoryQueryContainer,
            $productManagementQueryContainer,
            $productQueryContainer,
            $stockQueryContainer,
            $productFacade,
            $productImageFacade,
            $localeProvider,
            $currentLocale,
            $attributeCollection,
            $taxCollection,
            $imageUrlPrefix,
            $store
        );

        $this->attributeTransferCollection = new Collection($attributeCollection);
        $this->productStockHelper = $productStockHelper;
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param int|null $idProductAbstract
     * @param string|null $type
     *
     * @return mixed
     */
    public function getOptions($idProductAbstract = null, $type = null)
    {
        $formOptions = parent::getOptions($idProductAbstract);

        $formOptions[ProductConcreteFormAdd::OPTION_IS_BUNDLE_ITEM] = $type === ProductManagementConfig::PRODUCT_TYPE_BUNDLE;

        return $formOptions;
    }

    /**
     * @return array
     */
    protected function getDefaultStockFields()
    {
        $result = [];
        $stockTypeCollection = $this->stockQueryContainer->queryAllStockTypes()->find();

        foreach ($stockTypeCollection as $stockTypEntity) {
            $result[] = [
                StockForm::FIELD_HIDDEN_FK_STOCK => $stockTypEntity->getIdStock(),
                StockForm::FIELD_HIDDEN_STOCK_PRODUCT_ID => 0,
                StockForm::FIELD_IS_NEVER_OUT_OF_STOCK => false,
                StockForm::FIELD_TYPE => $stockTypEntity->getName(),
                StockForm::FIELD_QUANTITY => 0,
            ];
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    public function getData(ProductAbstractTransfer $productAbstractTransfer)
    {
        $formData = $this->getDefaultFormFields();

        $productConcreteTransfers = $this->productFacade->getConcreteProductsByAbstractProductId($productAbstractTransfer->getIdProductAbstract());
        $superAttributes = $this->productAttributeFacade->getUniqueSuperAttributesFromConcreteProducts($productConcreteTransfers);
//        dump($superAttributes);
//        die;

        return $formData;
    }
}
