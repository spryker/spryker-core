<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

interface PriceProductAbstractPublisherInterface
{
    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publish(array $priceProductStoreIds): void;

    /**
     * @param array $merchantRelationshipAbstractProducts
     *
     * @return void
     */
    public function unpublish(array $merchantRelationshipAbstractProducts): void;
}
