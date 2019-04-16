<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;

class ResourceShareReader implements ResourceShareReaderInterface
{
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND = 'resource_share.reader.error.resource_is_not_found';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface
     */
    protected $resourceShareValidator;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface $resourceShareValidator
     */
    public function __construct(
        ResourceShareRepositoryInterface $resourceShareRepository,
        ResourceShareValidatorInterface $resourceShareValidator
    ) {
        $this->resourceShareRepository = $resourceShareRepository;
        $this->resourceShareValidator = $resourceShareValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->resourceShareValidator->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $existingResourceShareTransfer = $this->resourceShareRepository->findResourceShare(
            $this->filterResourceShareTransfer($resourceShareTransfer)
        );

        if ($existingResourceShareTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(true)
                ->setResourceShare($existingResourceShareTransfer);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(false)
            ->addErrorMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    protected function filterResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): ResourceShareTransfer
    {
        return (new ResourceShareTransfer())
            ->setResourceType($resourceShareTransfer->getResourceType())
            ->setResourceData($resourceShareTransfer->getResourceData())
            ->setCustomerReference($resourceShareTransfer->getCustomerReference());
    }
}
