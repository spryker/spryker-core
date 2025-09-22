<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder;

use Generated\Shared\Transfer\RestSspServiceAttributesTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceTransfer;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use SprykerFeature\Glue\SelfServicePortal\SelfServicePortalConfig;

class SspServicesResponseBuilder implements SspServicesResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_SERVICE_NOT_FOUND = 'self_service_portal.service.error.not-found';

    public function __construct(
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected GlossaryStorageClientInterface $glossaryStorageClient
    ) {
    }

    public function createSspServiceCollectionRestResponse(
        SspServiceCollectionTransfer $sspServiceCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($sspServiceCollectionTransfer->getServices() as $sspServiceTransfer) {
            $serviceResource = $this->createSspServiceRestResource($sspServiceTransfer);
            $restResponse->addResource($serviceResource);
        }

        return $restResponse;
    }

    protected function createSspServiceRestResource(SspServiceTransfer $sspServiceTransfer): RestResourceInterface
    {
        $restSspServiceAttributesTransfer = $this->mapSspServiceTransferToRestSspServiceAttributesTransfer(
            $sspServiceTransfer,
            new RestSspServiceAttributesTransfer(),
        );

        return $this->restResourceBuilder->createRestResource(
            SelfServicePortalConfig::RESOURCE_SSP_SERVICES,
            $sspServiceTransfer->getUuidOrFail(),
            $restSspServiceAttributesTransfer,
        );
    }

    protected function mapSspServiceTransferToRestSspServiceAttributesTransfer(
        SspServiceTransfer $sspServiceTransfer,
        RestSspServiceAttributesTransfer $restSspServiceAttributesTransfer
    ): RestSspServiceAttributesTransfer {
        return $restSspServiceAttributesTransfer->fromArray($sspServiceTransfer->toArray(), true);
    }
}
