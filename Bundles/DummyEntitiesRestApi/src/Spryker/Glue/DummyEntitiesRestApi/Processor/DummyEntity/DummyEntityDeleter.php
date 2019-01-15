<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity;

use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class DummyEntityDeleter implements DummyEntityDeleterInterface
{
    /**
     * @var \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityReaderInterface
     */
    protected $dummyEntityReader;

    /**
     * @var \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface
     */
    protected $dummyEntitiesResourceMapper;

    /**
     * @var \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface
     */
    protected $dummyEntityRestResponseBuilder;

    /**
     * @param \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityReaderInterface $dummyEntityReader
     * @param \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface $dummyEntitiesResourceMapper
     * @param \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface $dummyEntityRestResponseBuilder
     */
    public function __construct(
        DummyEntityReaderInterface $dummyEntityReader,
        DummyEntityMapperInterface $dummyEntitiesResourceMapper,
        DummyEntityRestResponseBuilderInterface $dummyEntityRestResponseBuilder
    ) {
        $this->dummyEntityReader = $dummyEntityReader;
        $this->dummyEntitiesResourceMapper = $dummyEntitiesResourceMapper;
        $this->dummyEntityRestResponseBuilder = $dummyEntityRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteDummyEntity(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->dummyEntityRestResponseBuilder->createRestResponse();
        $uuidDummyEntity = $restRequest->getResource()->getId();

        if (!$uuidDummyEntity) {
            return $this->dummyEntityRestResponseBuilder->createDummyEntityIdMissingErrorRestResponse();
        }

        $dummyEntityTransfer = $this->dummyEntityReader->findDummyEntity($uuidDummyEntity);

        if (!$dummyEntityTransfer) {
            return $this->dummyEntityRestResponseBuilder->createDummyEntityNotFoundErrorRestResponse();
        }

        // TODO [Implement] Delete

        return $restResponse;
    }
}
