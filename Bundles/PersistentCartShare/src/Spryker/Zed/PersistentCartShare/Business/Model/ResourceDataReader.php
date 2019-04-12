<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business\Model;

use Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface;

class ResourceDataReader implements ResourceDataReaderInterface
{
    /**
     * @var \Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PersistentCartShareToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer
     */
    public function getResourceDataFromResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): PersistentCartShareResourceDataTransfer
    {
        $resourceData = $this->utilEncodingService->decodeJson($resourceShareTransfer->getResourceData(), true);

        return $this->mapResourceDataToPersistentCartShareDataTransfer($resourceData);
    }

    /**
     * @param array|null $resourceData
     *
     * @return \Generated\Shared\Transfer\PersistentCartShareResourceDataTransfer
     */
    protected function mapResourceDataToPersistentCartShareDataTransfer(?array $resourceData): PersistentCartShareResourceDataTransfer
    {
        return (new PersistentCartShareResourceDataTransfer())
            ->setIdQuote($resourceData['id_quote'] ?? null)
            ->setShareOption($resourceData['share_option'] ?? null);
    }
}
