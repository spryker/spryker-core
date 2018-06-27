<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

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
     * @param array $productAbstractIds
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstract();

    /**
     * @api
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryProductLabelLocalizedAttributes();
}
