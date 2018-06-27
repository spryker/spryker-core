<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

interface PriceProductAbstractStorageWriterInterface
{
    /**
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publish(array $businessUnitProducts): void;

    /**
     * @param array $merchantRelationshipAbstractProducts
     *
     * @return void
     */
    public function unpublish(array $merchantRelationshipAbstractProducts): void;
}
