<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryExportableProductsByLocale(array $productIds, LocaleTransfer $locale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryByProductAndLocale($idProduct, $idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMap();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $fkProductAttributeKey
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMapByFkProductAttributeKey($fkProductAttributeKey);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function querySearchPreferencesTable();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryFilterPreferencesTable();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryProductSearchAttribute();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryUnusedProductAttributeKeys();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryAllProductAttributeKeys();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param bool $synced
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryProductSearchAttributeBySynced($synced);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeArchiveQuery
     */
    public function queryProductSearchAttributeArchive();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param bool $synced
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMapBySynced($synced);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapArchiveQuery
     */
    public function queryProductSearchAttributeMapArchive();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $attributeNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractByAttributeName(array $attributeNames);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryProductSearch();
}
