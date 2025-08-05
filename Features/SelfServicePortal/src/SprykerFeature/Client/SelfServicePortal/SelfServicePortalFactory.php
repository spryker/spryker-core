<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReader;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Service\Reader\ShipmentTypeStorageReader;
use SprykerFeature\Client\SelfServicePortal\Service\Reader\ShipmentTypeStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\ShipmentType\Expander\ShipmentTypeProductViewExpander;
use SprykerFeature\Client\SelfServicePortal\ShipmentType\Expander\ShipmentTypeProductViewExpanderInterface;
use SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStub;
use SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStubInterface;

class SelfServicePortalFactory extends AbstractFactory
{
    public function createSelfServicePortalStub(): SelfServicePortalStubInterface
    {
        return new SelfServicePortalStub(
            $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_ZED_REQUEST),
        );
    }

    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader($this->getShipmentTypeStorageClient(), $this->getStoreClient());
    }

    public function createShipmentTypeProductViewExpander(): ShipmentTypeProductViewExpanderInterface
    {
        return new ShipmentTypeProductViewExpander($this->createShipmentTypeStorageReader());
    }

    public function createCmsBlockCompanyBusinessUnitStorageReader(): CmsBlockCompanyBusinessUnitStorageReaderInterface
    {
        return new CmsBlockCompanyBusinessUnitStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStoreClient(),
            $this->getLocaleClient(),
        );
    }

    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE);
    }

    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_LOCALE);
    }

    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORE);
    }

    public function getShipmentTypeStorageClient(): ShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }
}
