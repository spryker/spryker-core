<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductAbstractNotFoundException;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;

class ProductAbstractAttributeTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var string
     */
    protected const ATTRIBUTES_DEFAULT_SORT_DIRECTION_ASC = 'ASC';

    /**
     * @uses ProductAbstractAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME
     * @var string
     */
    protected const ATTRIBUTES_DEFAULT_SORT_FIELD = 'attribute_name';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param int $idProductAbstract
     */
    public function __construct(ProductMerchantPortalGuiToProductFacadeInterface $productFacade, int $idProductAbstract)
    {
        $this->productFacade = $productFacade;
        $this->idProductAbstract = $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        $productAttributeTableCriteriaTransfer = (new ProductAttributeTableCriteriaTransfer());

        $productAttributeTableCriteriaTransfer->setIdProduct($this->idProductAbstract);
        $productAttributeTableCriteriaTransfer->setOrderBy($guiTableDataRequestTransfer->getOrderBy());
        $productAttributeTableCriteriaTransfer->setOrderDirection($guiTableDataRequestTransfer->getOrderDirection());

        return $productAttributeTableCriteriaTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer $criteriaTransfer
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\ProductAbstractNotFoundException
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $attributes = [];
        /** @var \Generated\Shared\Transfer\ProductAttributeTableCriteriaTransfer $criteriaTransfer */
        $idProductAbstract = $criteriaTransfer->getIdProductOrFail();
        $productAbstract = $this->productFacade->findProductAbstractById($idProductAbstract);

        if (!$productAbstract) {
            throw new ProductAbstractNotFoundException((int)$idProductAbstract);
        }

        foreach ($productAbstract->getLocalizedAttributes() as $localizedAttribute) {
            $attributes = $this->addLocalizedAttributes($localizedAttribute, $attributes);
        }

        foreach ($productAbstract->getAttributes() as $attributeName => $value) {
            if (!isset($attributes[$attributeName])) {
                $attributes[$attributeName] = [
                    ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME => $attributeName,
                ];
            }

            $attributes[$attributeName][ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT] = $value;
        }

        foreach ($attributes as $attributeName => $values) {
            $attributes[$attributeName][ProductAbstractAttributeGuiTableConfigurationProvider::COL_KEY_ID_PRODUCT_ABSTRACT] = $this->idProductAbstract;
        }

        $attributes = $this->sortAttributesArray(
            $attributes,
            $criteriaTransfer->getOrderBy() ?? static::ATTRIBUTES_DEFAULT_SORT_FIELD,
            $criteriaTransfer->getOrderDirection() ?? static::ATTRIBUTES_DEFAULT_SORT_DIRECTION_ASC,
        );

        return $this->getGuiTableDataResponseTransfer($attributes);
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     * @param array<string[]> $data
     *
     * @return array<string[]>
     */
    protected function addLocalizedAttributes(
        LocalizedAttributesTransfer $localizedAttributesTransfer,
        array $data
    ): array {
        foreach ($localizedAttributesTransfer->getAttributes() as $attributeName => $value) {
            if (!isset($data[$attributeName])) {
                $data[$attributeName] = [
                    ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME => $attributeName,
                ];
            }

            $data[$attributeName][$localizedAttributesTransfer->getLocaleOrFail()->getLocaleName()] = $value;
        }

        return $data;
    }

    /**
     * @param array<string[]> $attributes
     * @param string $orderBy
     * @param string $orderDirection
     *
     * @return array
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
                    $comparableValue,
                );
            },
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
}
