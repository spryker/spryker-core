<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;

interface ProductOptionQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryAllProductOptionGroups();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryAllProductAbstractProductOptionGroups();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryAllProductOptionValues();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionByValueId($idProductOptionValue);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionByProductOptionCriteria(ProductOptionCriteriaTransfer $productOptionCriteriaTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueBySku($sku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $groupName
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByName($groupName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link queryTaxSetByIdProductOptionValueAndCountryIso2Code()} instead.
     *
     * @param int[] $allIdOptionValueUsages
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryTaxSetByIdProductOptionValueAndCountryIso2Code($allIdOptionValueUsages, $countryIso2Code);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $idProductOptionValues
     * @param string[] $countryIso2Codes
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryTaxSetByIdProductOptionValueAndCountryIso2Codes(array $idProductOptionValues, array $countryIso2Codes): SpyProductOptionValueQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupById($idProductOptionGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById($idProductOptionGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryActiveProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById($idProductOptionGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery
     */
    public function queryProductOptionValuePricesByIdProductOptionValue($idProductOptionValue);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionGroup
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryAbstractProductsByOptionGroupId($idProductOptionGroup, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $term
     * @param int $idProductOptionGroup
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductsAbstractBySearchTermForAssignment($term, $idProductOptionGroup, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValue($value);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrder();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByProductOptionValueId(int $idProductOptionValue): SpyProductOptionGroupQuery;
}
