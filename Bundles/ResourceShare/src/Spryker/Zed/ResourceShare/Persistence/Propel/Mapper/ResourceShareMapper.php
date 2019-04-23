<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShare;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface;

class ResourceShareMapper
{
    /**
     * @var \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ResourceShareToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Orm\Zed\ResourceShare\Persistence\SpyResourceShare $resourceShareEntity
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function mapResourceShareEntityToResourceShareTransfer(SpyResourceShare $resourceShareEntity): ResourceShareTransfer
    {
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->fromArray($resourceShareEntity->toArray(), true);

        $resourceShareDataTransfer = (new ResourceShareDataTransfer())
            ->setData($this->utilEncodingService->decodeJson($resourceShareEntity->getResourceData(), true));

        return $resourceShareTransfer->setResourceData($resourceShareDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShare
     */
    public function mapResourceShareTransferToResourceShareEntity(ResourceShareTransfer $resourceShareTransfer): SpyResourceShare
    {
        $resourceShareEntity = new SpyResourceShare();
        $resourceShareEntity->fromArray($resourceShareTransfer->toArray());

        $resourceShareDataTransfer = $resourceShareTransfer->getResourceData();

        $resourceShareEntity->setResourceData(
            $this->utilEncodingService->encodeJson($resourceShareDataTransfer->getData())
        );

        return $resourceShareEntity;
    }
}
