<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductLabelStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery
     */
    public function queryProductAbstractLabelStorageByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery
     */
    public function queryProductLabelDictionaryStorage();

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByProductAbstractIds(array $productAbstractIds);

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int[] $productLabelProductAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByIds(array $productLabelProductAbstractIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_product_label_product_abstract` filtered by primary ids.
     *
     * @api
     *
     * @param int[] $productLabelProductAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByPrimaryIds(array $productLabelProductAbstractIds): SpyProductLabelProductAbstractQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstract();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryProductLabelLocalizedAttributes();
}
