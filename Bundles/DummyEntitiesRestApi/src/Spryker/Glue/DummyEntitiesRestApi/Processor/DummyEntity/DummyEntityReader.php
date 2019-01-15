<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity;

use Generated\Shared\Transfer\DummyEntityCollectionTransfer;
use Generated\Shared\Transfer\DummyEntityTransfer;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class DummyEntityReader implements DummyEntityReaderInterface
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
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getDummyEntityCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->dummyEntityRestResponseBuilder->createRestResponse();

        // TODO [Implement] Read collection
        $dummyEntityCollectionTransfer = new DummyEntityCollectionTransfer();

        foreach ($dummyEntityCollectionTransfer->getDummyEntities() as $dummyEntityTransfer) {
            $restResponse->addResource(
                $this->dummyEntityRestResponseBuilder->createDummyEntityRestResource($dummyEntityTransfer)
            );
        }

        return $restResponse;
    }

    /**
     * @param string $uuidDummyEntity
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getDummyEntity(
        string $uuidDummyEntity,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restResponse = $this->dummyEntityRestResponseBuilder->createRestResponse();

        if (!$uuidDummyEntity) {
            return $this->dummyEntityRestResponseBuilder->createDummyEntityIdMissingErrorRestResponse();
        }

        $dummyEntityTransfer = $this->findDummyEntity($uuidDummyEntity);

        if (!$dummyEntityTransfer) {
            return $this->dummyEntityRestResponseBuilder->createDummyEntityNotFoundErrorRestResponse();
        }

        return $restResponse->addResource(
            $this->dummyEntityRestResponseBuilder->createDummyEntityRestResource($dummyEntityTransfer)
        );
    }

    /**
     * @param string $uuidDummyEntity
     *
     * @return \Generated\Shared\Transfer\DummyEntityTransfer|null
     */
    public function findDummyEntity(string $uuidDummyEntity): ?DummyEntityTransfer
    {
        $dummyEntityTransfer = (new DummyEntityTransfer())
            ->setUuid($uuidDummyEntity);

        // TODO [Implement] Read one

        return $dummyEntityTransfer;
    }
}
