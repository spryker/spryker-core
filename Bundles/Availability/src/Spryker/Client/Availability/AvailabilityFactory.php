<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability;

use Spryker\Client\Availability\KeyBuilder\AvailabilityResourceKeyBuilder;
use Spryker\Client\Availability\Storage\AvailabilityStorage;
use Spryker\Client\Kernel\AbstractFactory;

class AvailabilityFactory extends AbstractFactory
{

    /**
     * @deprecated Use AvailabilityFactory::createCurrentLocaleAvailabilityStorage
     *
     * @param string $locale
     *
     * @return \Spryker\Client\Availability\Storage\AvailabilityStorageInterface
     */
    public function createAvailabilityStorage($locale)
    {
        return new AvailabilityStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $locale
        );
    }

    /**
     * @return \Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new AvailabilityResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Availability\Dependency\Client\AvailabilityToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\Availability\Storage\AvailabilityStorage
     */
    public function createCurrentLocaleAvailabilityStorage()
    {
        return new AvailabilityStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $this->getLocaleClient()->getCurrentLocale()
        );
    }

}
