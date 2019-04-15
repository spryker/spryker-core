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
    protected const GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED = 'resource_share.reader.error.resource_type_is_not_defined';
    protected const GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'resource_share.reader.error.customer_reference_is_not_defined';
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND = 'resource_share.reader.error.resource_is_not_found';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     */
    public function __construct(ResourceShareRepositoryInterface $resourceShareRepository)
    {
        $this->resourceShareRepository = $resourceShareRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $existingResourceShareTransfer = $this->resourceShareRepository->findResourceShare(
            $this->filterResourceShareTransfer($resourceShareTransfer)
        );

        if ($existingResourceShareTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(true)
                ->setResourceShare($resourceShareTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function validateResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
            ->setIsSuccessful(false);

        if (!$resourceShareTransfer->getResourceType()) {
            return $resourceShareResponseTransfer->addErrorMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED)
            );
        }

        if (!$resourceShareTransfer->getCustomerReference()) {
            return $resourceShareResponseTransfer->addErrorMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED)
            );
        }

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
