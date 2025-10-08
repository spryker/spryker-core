<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal;

use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Builder\SspAssetsResponseBuilder as BackendSspAssetsResponseBuilder;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Builder\SspAssetsResponseBuilderInterface as BackendSspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Creator\SspAssetsCreator as BackendSspAssetsCreator;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Creator\SspAssetsCreatorInterface as BackendSspAssetsCreatorInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper\SspAssetsMapper as BackendSspAssetsMapper;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper\SspAssetsMapperInterface as BackendSspAssetsMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Reader\SspAssetsReader as BackendSspAssetsReader;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Reader\SspAssetsReaderInterface as BackendSspAssetsReaderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Updater\SspAssetsUpdater;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Updater\SspAssetsUpdaterInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspAssetsResponseBuilder;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspInquiriesResponseBuilder;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspInquiriesResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspServicesResponseBuilder;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspServicesResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator\SspAssetsCreator;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator\SspAssetsCreatorInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator\SspInquiriesCreator;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator\SspInquiriesCreatorInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspAssetsMapper;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspAssetsMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspInquiriesMapper;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspInquiriesMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspServicesMapper;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspServicesMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspAssetsReader;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspAssetsReaderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspInquiriesReader;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspInquiriesReaderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspServicesReader;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspServicesReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;

/**
 * @method \SprykerFeature\Glue\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SelfServicePortalFactory extends AbstractBackendApiFactory
{
    public function createAssetsReader(): SspAssetsReaderInterface
    {
        return new SspAssetsReader(
            $this->getSelfServicePortalClient(),
            $this->createRestAssetsResponseBuilder(),
            $this->createAssetsMapper(),
        );
    }

    public function createAssetsCreator(): SspAssetsCreatorInterface
    {
        return new SspAssetsCreator(
            $this->getSelfServicePortalClient(),
            $this->createAssetsMapper(),
            $this->createRestAssetsResponseBuilder(),
        );
    }

    public function createAssetsMapper(): SspAssetsMapperInterface
    {
        return new SspAssetsMapper();
    }

    public function createRestAssetsResponseBuilder(): SspAssetsResponseBuilderInterface
    {
        return new SspAssetsResponseBuilder(
            $this->getResourceBuilder(),
            $this->getGlossaryStorageClient(),
        );
    }

    public function getGlossaryStorageClient(): GlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORE);
    }

    public function getSelfServicePortalClient(): SelfServicePortalClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SELF_SERVICE_PORTAL);
    }

    public function createInquiriesReader(): SspInquiriesReaderInterface
    {
        return new SspInquiriesReader(
            $this->getSelfServicePortalClient(),
            $this->createRestInquiriesResponseBuilder(),
            $this->createInquiriesMapper(),
        );
    }

    public function createInquiriesCreator(): SspInquiriesCreatorInterface
    {
        return new SspInquiriesCreator(
            $this->getSelfServicePortalClient(),
            $this->createRestInquiriesResponseBuilder(),
            $this->createInquiriesMapper(),
        );
    }

    public function createInquiriesMapper(): SspInquiriesMapperInterface
    {
        return new SspInquiriesMapper($this->getStoreClient());
    }

    public function createRestInquiriesResponseBuilder(): SspInquiriesResponseBuilderInterface
    {
        return new SspInquiriesResponseBuilder(
            $this->getResourceBuilder(),
            $this->getGlossaryStorageClient(),
            $this->createInquiriesMapper(),
        );
    }

    public function createSspAssetsReader(): BackendSspAssetsReaderInterface
    {
        return new BackendSspAssetsReader(
            $this->getSelfServicePortalFacade(),
            $this->createSspAssetsResponseBuilder(),
            $this->createSspAssetsMapper(),
        );
    }

    public function createSspAssetsCreator(): BackendSspAssetsCreatorInterface
    {
        return new BackendSspAssetsCreator(
            $this->getSelfServicePortalFacade(),
            $this->createSspAssetsResponseBuilder(),
            $this->createSspAssetsMapper(),
        );
    }

    public function createSspAssetsUpdater(): SspAssetsUpdaterInterface
    {
        return new SspAssetsUpdater(
            $this->getSelfServicePortalFacade(),
            $this->createSspAssetsResponseBuilder(),
            $this->createSspAssetsMapper(),
        );
    }

    public function createSspAssetsMapper(): BackendSspAssetsMapperInterface
    {
        return new BackendSspAssetsMapper($this->getSelfServicePortalFacade());
    }

    public function createSspAssetsResponseBuilder(): BackendSspAssetsResponseBuilderInterface
    {
        return new BackendSspAssetsResponseBuilder(
            $this->getConfig(),
            $this->getGlossaryStorageClient(),
            $this->createSspAssetsMapper(),
        );
    }

    public function getSelfServicePortalFacade(): SelfServicePortalFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SELF_SERVICE_PORTAL);
    }

    public function createServicesReader(): SspServicesReaderInterface
    {
        return new SspServicesReader(
            $this->getSelfServicePortalClient(),
            $this->createRestServicesResponseBuilder(),
            $this->createServicesMapper(),
        );
    }

    public function createServicesMapper(): SspServicesMapperInterface
    {
        return new SspServicesMapper($this->getStoreClient());
    }

    public function createRestServicesResponseBuilder(): SspServicesResponseBuilderInterface
    {
        return new SspServicesResponseBuilder(
            $this->getResourceBuilder(),
            $this->getGlossaryStorageClient(),
        );
    }
}
