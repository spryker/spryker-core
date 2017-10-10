<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Synchronization\Business\Model\Search\SynchronizationSearch;
use Spryker\Zed\Synchronization\Business\Model\Storage\SynchronizationStorage;
use Spryker\Zed\Synchronization\Business\Model\Validation\OutdatedValidator;
use Spryker\Zed\Synchronization\SynchronizationDependencyProvider;

/**
 * @method \Spryker\Zed\Synchronization\SynchronizationConfig getConfig()
 */
class SynchronizationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Synchronization\Business\Model\SynchronizationInterface
     */
    public function createStorageManager()
    {
        return new SynchronizationStorage(
            $this->getStorageClient(),
            $this->getUtilEncodingService(),
            $this->createOutdatedValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Model\SynchronizationInterface
     */
    public function createSearchManager()
    {
        return new SynchronizationSearch(
            $this->getSearchClient(),
            $this->createOutdatedValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Model\Validation\OutdatedValidatorInterface
     */
    public function createOutdatedValidator()
    {
        return new OutdatedValidator(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
