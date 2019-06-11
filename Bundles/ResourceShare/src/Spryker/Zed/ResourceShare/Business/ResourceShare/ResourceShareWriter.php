<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;

class ResourceShareWriter implements ResourceShareWriterInterface
{
    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface
     */
    protected $resourceShareEntityManager;

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface
     */
    protected $resourceShareValidator;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface $resourceShareEntityManager
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface $resourceShareValidator
     */
    public function __construct(
        ResourceShareEntityManagerInterface $resourceShareEntityManager,
        ResourceShareRepositoryInterface $resourceShareRepository,
        ResourceShareValidatorInterface $resourceShareValidator
    ) {
        $this->resourceShareEntityManager = $resourceShareEntityManager;
        $this->resourceShareRepository = $resourceShareRepository;
        $this->resourceShareValidator = $resourceShareValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer->requireResourceShare();
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();

        $resourceShareResponseTransfer = $this->resourceShareValidator->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        return $this->findOrCreateResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function findOrCreateResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShare = $this->findExistingResourceShare($resourceShareTransfer);
        if ($resourceShare === null) {
            $resourceShare = $this->resourceShareEntityManager->createResourceShare($resourceShareTransfer);
        }

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShare);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    protected function findExistingResourceShare(ResourceShareTransfer $resourceShareTransfer): ?ResourceShareTransfer
    {
        return $this->resourceShareRepository->findResourceShareByUuid(
            $this->resourceShareEntityManager->buildResourceShare($resourceShareTransfer)->getUuid()
        );
    }
}
