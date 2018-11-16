<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductSetPageSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productSetIds
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery
     */
    public function queryProductSetDataByIds(array $productSetIds);

    /**
     * @api
     *
     * @param array $productSetIds
     *
     * @return \Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearchQuery
     */
    public function queryProductSetPageSearchPageByIds(array $productSetIds);

    /**
     * @api
     *
     * @param array $productImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductSetIdsByProductImageIds(array $productImageIds);

    /**
     * @api
     *
     * @param array $productImageSetToProductImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductSetIdsByProductImageSetToProductImageIds(array $productImageSetToProductImageIds);

    /**
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetByIds(array $ids);
}
