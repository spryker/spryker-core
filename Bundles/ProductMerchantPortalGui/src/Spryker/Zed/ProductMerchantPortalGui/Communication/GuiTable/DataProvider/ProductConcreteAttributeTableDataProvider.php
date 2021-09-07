<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;

class ProductConcreteAttributeTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var string
     */
    protected const ATTRIBUTES_DEFAULT_SORT_DIRECTION_ASC = 'ASC';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductConcreteAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME
     * @var string
     */
    protected const ATTRIBUTES_DEFAULT_SORT_FIELD = 'attribute_name';
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductConcreteAttributeGuiTableConfigurationProvider::COL_KEY_ID_PRODUCT_CONCRETE
     * @var string
     */
    protected const COL_KEY_ID_PRODUCT_CONCRETE = 'idProductConcrete';
    /**
     * @var string
     */
    protected const COL_KEY_ID_IS_SUPER = 'is_super';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface
     */
    protected $localizedAttributesExtractor;

    /**
     * @var int
     */
    protected $idProductConcrete;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface $localizedAttributesExtractor
     * @param int $idProductConcrete
     */
    public function __construct(
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        LocalizedAttributesExtractorInterface $localizedAttributesExtractor,
        int $idProductConcrete
    ) {
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
        $this->localizedAttributesExtractor = $localizedAttributesExtractor;
        $this->idProductConcrete = $idProductConcrete;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        $productAttributeTableCriteriaTransfer = (new ProductAttributeTableCriteriaTransfer());

        $productAttributeTableCriteriaTransfer->setIdProduct($this->idProductConcrete);
        $productAttributeTableCriteriaTransfer->setOrderBy($guiTableDataRequestTransfer->getOrderBy());
        $productAttributeTableCriteriaTransfer->setOrderDirection($guiTableDataRequestTransfer->getOrderDirection());

        return $productAttributeTableCriteriaTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer $criteriaTransfer
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductConcreteNotFoundException
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $attributes = [];
        /** @var \Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer $criteriaTransfer */
        $idProductConcrete = $criteriaTransfer->getIdProductOrFail();
        $productConcreteTransfer = $this->productFacade->findProductConcreteById($idProductConcrete);

        if (!$productConcreteTransfer) {
            throw new ProductConcreteNotFoundException((int)$idProductConcrete);
        }

        $superAttributeNames = $this->getSuperAttributeNames($productConcreteTransfer);

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $attributes = $this->appendAttributes(
                $localizedAttributesTransfer->getAttributes(),
                $localizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail(),
                $attributes
            );
        }

        $attributes = $this->appendAttributes(
            $productConcreteTransfer->getAttributes(),
            ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT,
            $attributes
        );

        foreach ($attributes as $attributeName => $values) {
            $attributes[$attributeName][static::COL_KEY_ID_PRODUCT_CONCRETE] = $this->idProductConcrete;
            $attributes[$attributeName][static::COL_KEY_ID_IS_SUPER] = isset($superAttributeNames[$attributeName]);
        }

        $attributes = $this->sortAttributesArray(
            $attributes,
            $criteriaTransfer->getOrderBy() ?? static::ATTRIBUTES_DEFAULT_SORT_FIELD,
            $criteriaTransfer->getOrderDirection() ?? static::ATTRIBUTES_DEFAULT_SORT_DIRECTION_ASC
        );

        return $this->getGuiTableDataResponseTransfer($attributes);
    }

    /**
     * @param array $attributes
     * @param string $columnName
     * @param string[][] $data
     *
     * @return string[][]
     */
    protected function appendAttributes(
        array $attributes,
        string $columnName,
        array $data
    ): array {
        foreach ($attributes as $attributeName => $value) {
            if (!isset($data[$attributeName])) {
                $data[$attributeName] = [
                    ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME => $attributeName,
                ];
            }
            $data[$attributeName][$columnName] = $value;
        }

        return $data;
    }

    /**
     * @param array $attributes
     * @param string $orderBy
     * @param string $orderDirection
     *
     * @return string[][]
     */
    protected function sortAttributesArray(array $attributes, string $orderBy, string $orderDirection): array
    {
        if (empty($attributes)) {
            return $attributes;
        }

        $direction = $orderDirection === static::ATTRIBUTES_DEFAULT_SORT_DIRECTION_ASC;

        usort(
            $attributes,
            function (array $comparable, array $comparator) use ($orderBy, $direction) {
                $comparableValue = $comparable[$orderBy] ?? '';
                $comparatorValue = $comparator[$orderBy] ?? '';

                return $direction ? strcasecmp($comparableValue, $comparatorValue) : strcasecmp(
                    $comparatorValue,
                    $comparableValue
                );
            }
        );

        return $attributes;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function getGuiTableDataResponseTransfer(array $data): GuiTableDataResponseTransfer
    {
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach (array_values($data) as $row) {
            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($row));
        }

        $guiTableDataResponseTransfer->setTotal(count($data));

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string[]
     */
    protected function getSuperAttributeNames(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        return $this->localizedAttributesExtractor->extractCombinedSuperAttributeNames(
            $productConcreteTransfer->getAttributes(),
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer
        );
    }
}
