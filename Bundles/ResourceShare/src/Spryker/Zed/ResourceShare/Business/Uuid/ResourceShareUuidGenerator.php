<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\Uuid;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilUuidGeneratorServiceInterface;

class ResourceShareUuidGenerator implements ResourceShareUuidGeneratorInterface
{
    /**
     * @var \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilUuidGeneratorServiceInterface
     */
    protected $utilUuidGeneratorService;

    /**
     * @param \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService
     */
    public function __construct(
        ResourceShareToUtilEncodingServiceInterface $utilEncodingService,
        ResourceShareToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->utilUuidGeneratorService = $utilUuidGeneratorService;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return string
     */
    public function generateResourceShareUuid(ResourceShareTransfer $resourceShareTransfer): string
    {
        $resourceShareJsonData = $this->getResourceShareJsonData($resourceShareTransfer);

        return $this->utilUuidGeneratorService->generateUuid5FromObjectId($resourceShareJsonData);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return string
     */
    protected function getResourceShareJsonData(ResourceShareTransfer $resourceShareTransfer): string
    {
        $resourceShareData = $resourceShareTransfer->toArray();

        return $this->utilEncodingService->encodeJson($resourceShareData);
    }
}
