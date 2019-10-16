<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability;

use Spryker\Client\Availability\Dependency\Client\AvailabilityToLocaleInterface;
use Spryker\Client\Availability\KeyBuilder\AvailabilityResourceKeyBuilder;
use Spryker\Client\Availability\Storage\AvailabilityStorage;
use Spryker\Client\Availability\Storage\AvailabilityStorageInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class AvailabilityFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Availability\Storage\AvailabilityStorageInterface
     */
    public function createCurrentLocaleAvailabilityStorage(): AvailabilityStorageInterface
    {
        return new AvailabilityStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $this->getLocaleClient()->getCurrentLocale()
        );
    }

    /**
     * @return \Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface
     */
    public function getStorage()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    public function createKeyBuilder(): KeyBuilderInterface
    {
        return new AvailabilityResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Availability\Dependency\Client\AvailabilityToLocaleInterface
     */
    public function getLocaleClient(): AvailabilityToLocaleInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::CLIENT_LOCALE);
    }
}
