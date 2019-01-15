<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity;

use Generated\Shared\Transfer\DummyEntityTransfer;
use Generated\Shared\Transfer\RestDummyEntityAttributesTransfer;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class DummyEntityCreator implements DummyEntityCreatorInterface
{
    /**
     * @var \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface
     */
    protected $dummyEntitiesResourceMapper;

    /**
     * @var \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface
     */
    protected $dummyEntityRestResponseBuilder;

    /**
     * @param \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface $dummyEntitiesResourceMapper
     * @param \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface $dummyEntityRestResponseBuilder
     */
    public function __construct(
        DummyEntityMapperInterface $dummyEntitiesResourceMapper,
        DummyEntityRestResponseBuilderInterface $dummyEntityRestResponseBuilder
    ) {
        $this->dummyEntitiesResourceMapper = $dummyEntitiesResourceMapper;
        $this->dummyEntityRestResponseBuilder = $dummyEntityRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createDummyEntity(
        RestRequestInterface $restRequest,
        RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->dummyEntityRestResponseBuilder->createRestResponse();

        $dummyEntityTransfer = $this->dummyEntitiesResourceMapper->mapRestDummyEntityAttributesTransferToDummyEntityTransfer(
            $restDummyEntityAttributesTransfer,
            new DummyEntityTransfer()
        );

        // TODO [Implement] Create

        return $restResponse->addResource(
            $this->dummyEntityRestResponseBuilder->createDummyEntityRestResource($dummyEntityTransfer)
        );
    }
}
