<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\DummyEntityTransfer;
use Generated\Shared\Transfer\RestDummyEntityAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\DummyEntitiesRestApi\DummyEntitiesRestApiConfig;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class DummyEntityRestResponseBuilder implements DummyEntityRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface
     */
    protected $dummyEntitiesResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface $dummyEntitiesResourceMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        DummyEntityMapperInterface $dummyEntitiesResourceMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->dummyEntitiesResourceMapper = $dummyEntitiesResourceMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\DummyEntityTransfer $dummyEntityTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createDummyEntityRestResource(DummyEntityTransfer $dummyEntityTransfer): RestResourceInterface
    {
        $restDummyEntityAttributesTransfer = $this->dummyEntitiesResourceMapper->mapDummyEntityTransferToRestDummyEntityAttributesTransfer(
            $dummyEntityTransfer,
            new RestDummyEntityAttributesTransfer()
        );

        return $this->restResourceBuilder->createRestResource(
            DummyEntitiesRestApiConfig::RESOURCE_DUMMY_ENTITIES,
            $dummyEntityTransfer->getUuid(),
            $restDummyEntityAttributesTransfer
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createDummyEntityIdMissingErrorRestResponse(): RestResponseInterface
    {
        return $this->createRestResponse()->addError(
            (new RestErrorMessageTransfer())
                ->setCode(DummyEntitiesRestApiConfig::RESPONSE_CODE_DUMMY_ENTITY_ID_NOT_SPECIFIED)
                ->setStatus(HttpCode::BAD_REQUEST)
                ->setDetail(DummyEntitiesRestApiConfig::RESPONSE_DETAIL_DUMMY_ENTITY_ID_NOT_SPECIFIED)
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createDummyEntityNotFoundErrorRestResponse(): RestResponseInterface
    {
        return $this->createRestResponse()->addError(
            (new RestErrorMessageTransfer())
                ->setCode(DummyEntitiesRestApiConfig::RESPONSE_CODE_DUMMY_ENTITY_NOT_FOUND)
                ->setStatus(HttpCode::NOT_FOUND)
                ->setDetail(DummyEntitiesRestApiConfig::RESPONSE_DETAIL_DUMMY_ENTITY_NOT_FOUND)
        );
    }
}
