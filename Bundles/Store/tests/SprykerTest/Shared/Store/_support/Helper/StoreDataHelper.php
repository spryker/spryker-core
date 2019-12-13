<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Store\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class StoreDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var array
     */
    protected $storeNameMap = [];

    /**
     * @param array $storeOverride
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveStore(array $storeOverride = [])
    {
        $storeTransfer = (new StoreBuilder($storeOverride))->build();
        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeTransfer->getName())
            ->findOneOrCreate();
        $storeEntity->save();

        $storeTransfer->fromArray($storeEntity->toArray());

        return $storeTransfer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function grabStoreByName(string $name): StoreTransfer
    {
        if (!$this->storeNameMap) {
            foreach ($this->getLocator()->store()->facade()->getAllStores() as $storeTransfer) {
                $this->storeNameMap[$storeTransfer->getName()] = $storeTransfer;
            }
        }

        if (empty($this->storeNameMap[$name])) {
            throw new \InvalidArgumentException(sprintf('Store "%s" does not exist', $name));
        }

        return $this->storeNameMap[$name];
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function grabDefaultStore(): StoreTransfer
    {
        return $this->grabStoreByName(APPLICATION_STORE);
    }
}
