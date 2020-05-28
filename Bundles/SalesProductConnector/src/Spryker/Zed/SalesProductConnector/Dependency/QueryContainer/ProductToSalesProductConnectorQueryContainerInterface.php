<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Dependency\QueryContainer;

interface ProductToSalesProductConnectorQueryContainerInterface
{
    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku);

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct();
}
