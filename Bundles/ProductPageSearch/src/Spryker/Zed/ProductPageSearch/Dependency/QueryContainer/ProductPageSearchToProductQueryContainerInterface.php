<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\QueryContainer;

interface ProductPageSearchToProductQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryAllProductAbstractLocalizedAttributes();

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryAllProductLocalizedAttributes();

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct();

}
