<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductDiscountConnector\Persistence;

interface ProductDiscountConnectorQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function queryProductAttributeKeys();
}
