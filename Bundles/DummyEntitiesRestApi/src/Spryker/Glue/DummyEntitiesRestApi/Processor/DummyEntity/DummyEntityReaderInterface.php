<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity;

use Generated\Shared\Transfer\DummyEntityTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface DummyEntityReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getDummyEntityCollection(RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * @param string $uuidDummyEntity
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getDummyEntity(
        string $uuidDummyEntity,
        RestRequestInterface $restRequest
    ): RestResponseInterface;

    /**
     * @param string $uuidDummyEntity
     *
     * @return \Generated\Shared\Transfer\DummyEntityTransfer|null
     */
    public function findDummyEntity(string $uuidDummyEntity): ?DummyEntityTransfer;
}
