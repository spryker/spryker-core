<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Store\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StoreBuilder;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class StoreDataHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $storeOverride
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveStore($storeOverride = [])
    {
        $storeTransfer = (new StoreBuilder($storeOverride))->build();
        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeTransfer->getName())
            ->findOneOrCreate();
        $storeEntity->save();

        $storeTransfer->fromArray($storeEntity->toArray());

        return $storeTransfer;
    }
}
