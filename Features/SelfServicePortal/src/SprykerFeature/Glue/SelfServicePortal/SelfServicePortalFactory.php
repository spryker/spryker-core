<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal;

use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspAssetsResponseBuilder;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator\SspAssetsCreator;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator\SspAssetsCreatorInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspAssetsMapper;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspAssetsMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspAssetsReader;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader\SspAssetsReaderInterface;

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

    public function getSelfServicePortalClient(): SelfServicePortalClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SELF_SERVICE_PORTAL);
    }
}
