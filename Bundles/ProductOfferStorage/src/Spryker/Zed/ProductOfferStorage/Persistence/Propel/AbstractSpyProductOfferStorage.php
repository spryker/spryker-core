<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Persistence\Propel;

use Orm\Zed\ProductOfferStorage\Persistence\Base\SpyProductOfferStorage as BaseSpyProductOfferStorage;
use Spryker\Zed\Propel\Persistence\BatchEntityHooksInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_product_offer_storage' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AbstractSpyProductOfferStorage extends BaseSpyProductOfferStorage implements BatchEntityHooksInterface
{
    /**
     * @return void
     */
    public function batchPreSaveHook(): void
    {
        if (method_exists($this, 'isSynchronizationEnabled') && $this->isSynchronizationEnabled()) {
            // synchronization behavior
            $this->setGeneratedKey();
            $this->setGeneratedKeyForMappingResource();
            $this->setGeneratedAliasKeys();
        }
    }

    /**
     * @return void
     */
    public function batchPostSaveHook(): void
    {
        if (method_exists($this, 'isSynchronizationEnabled') && $this->isSynchronizationEnabled()) {
            // synchronization behavior
            $this->syncPublishedMessage();
            $this->syncPublishedMessageForMappingResource();
            $this->syncPublishedMessageForMappings();
        }
    }
}
