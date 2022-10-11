<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Store\Helper;

use Codeception\Module;
use ReflectionClass;
use Spryker\Shared\Store\StoreConstants;
use Spryker\Zed\Store\Business\Cache\StoreCache;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

class StoreReferenceHelper extends Module
{
    use ConfigHelperTrait;

    /**
     * @param array<string, string> $storeReferenceData
     *
     * @return void
     */
    public function setStoreReferenceData(array $storeReferenceData): void
    {
        $this->resetStoreCache();
        $this->getConfigHelper()->mockEnvironmentConfig(StoreConstants::STORE_NAME_REFERENCE_MAP, $storeReferenceData);
    }

    /**
     * @return void
     */
    public function resetStoreCache(): void
    {
        $reflectedStoreCache = new ReflectionClass(StoreCache::class);
        $staticProperties = $reflectedStoreCache->getStaticProperties() ?? [];

        foreach ($staticProperties as $propertyName => $propertyValue) {
            $reflectedProperty = $reflectedStoreCache->getProperty($propertyName);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue([]);
        }
    }
}
