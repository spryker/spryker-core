<?php
/**
 * Created by PhpStorm.
 * User: smarovydlo
 * Date: 12/2/19
 * Time: 4:10 PM
 */

namespace Spryker\ProductOfferAvailabilityStorage\src\Spryker\Zed\ProductOfferAvailabilityStorage\Communication;

use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageDependencyProvider;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReader;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReaderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class ProductOfferAvailabilityStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReaderInterface
     */
    public function createProductOfferAvailabilityStorageReader(): ProductOfferAvailabilityStorageReaderInterface
    {
        return new ProductOfferAvailabilityStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductOfferAvailabilityStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductOfferAvailabilityStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
