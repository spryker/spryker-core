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
     * @api
     *
     * @param array $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryExportableProductsByLocale(array $productIds, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryByProductAndLocale($idProduct, $idLocale);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMap();

    /**
     * @api
     *
     * @param int $fkProductAttributeKey
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMapByFkProductAttributeKey($fkProductAttributeKey);

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function querySearchPreferencesTable();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryFilterPreferencesTable();

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryProductSearchAttribute();

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryUnusedProductAttributeKeys();

}
