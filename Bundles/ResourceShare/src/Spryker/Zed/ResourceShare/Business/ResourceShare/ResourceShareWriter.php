<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Business\Uuid\ResourceShareUuidGeneratorInterface;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;

class ResourceShareWriter implements ResourceShareWriterInterface
{
    protected const ERROR_MESSAGE_RESOURCE_TYPE_IS_NOT_DEFINED = 'Resource type is not defined.';
    protected const ERROR_MESSAGE_RESOURCE_DATA_IS_NOT_DEFINED = 'Resource data is not defined.';
    protected const ERROR_MESSAGE_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'Customer reference is not defined.';
    protected const ERROR_MESSAGE_RESOURCE_SHARE_ALREADY_EXISTS = 'Resource share already exists.';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface
     */
    protected $resourceShareEntityManager;

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\Uuid\ResourceShareUuidGeneratorInterface
     */
    protected $resourceShareUuidGenerator;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface $resourceShareEntityManager
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     * @param \Spryker\Zed\ResourceShare\Business\Uuid\ResourceShareUuidGeneratorInterface $resourceShareUuidGenerator
     */
    public function __construct(
        ResourceShareEntityManagerInterface $resourceShareEntityManager,
        ResourceShareRepositoryInterface $resourceShareRepository,
        ResourceShareUuidGeneratorInterface $resourceShareUuidGenerator
    ) {
        $this->resourceShareEntityManager = $resourceShareEntityManager;
        $this->resourceShareRepository = $resourceShareRepository;
        $this->resourceShareUuidGenerator = $resourceShareUuidGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->preValidateResourceShareTransfer(
            $resourceShareTransfer,
            new ResourceShareResponseTransfer()
        );

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareTransfer = $this->createResourceShare($resourceShareTransfer);
        if (!$resourceShareTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addErrorMessage(
                    (new MessageTransfer())->setValue(static::ERROR_MESSAGE_RESOURCE_SHARE_ALREADY_EXISTS)
                );
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    protected function createResourceShare(ResourceShareTransfer $resourceShareTransfer): ?ResourceShareTransfer
    {
        $resourceShareTransfer->setUuid(
            $this->resourceShareUuidGenerator->generateResourceShareUuid($resourceShareTransfer)
        );

        return $this->resourceShareEntityManager->createResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function preValidateResourceShareTransfer(
        ResourceShareTransfer $resourceShareTransfer,
        ResourceShareResponseTransfer $resourceShareResponseTransfer
    ): ResourceShareResponseTransfer {
        $resourceShareResponseTransfer->setIsSuccessful(false);

        if (!$resourceShareTransfer->getResourceType()) {
            return $resourceShareResponseTransfer->addErrorMessage(
                (new MessageTransfer())->setValue(static::ERROR_MESSAGE_RESOURCE_TYPE_IS_NOT_DEFINED)
            );
        }

        if (!$resourceShareTransfer->getCustomerReference()) {
            return $resourceShareResponseTransfer->addErrorMessage(
                (new MessageTransfer())->setValue(static::ERROR_MESSAGE_CUSTOMER_REFERENCE_IS_NOT_DEFINED)
            );
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true);
    }
}
