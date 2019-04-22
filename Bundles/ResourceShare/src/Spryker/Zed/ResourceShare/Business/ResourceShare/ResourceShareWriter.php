<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;

class ResourceShareWriter implements ResourceShareWriterInterface
{
    protected const GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED = 'resource_share.generation.error.resource_type_is_not_defined';
    protected const GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'resource_share.generation.error.customer_reference_is_not_defined';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface
     */
    protected $resourceShareEntityManager;

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareExpanderInterface
     */
    protected $resourceShareExpander;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface
     */
    protected $resourceShareValidator;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface $resourceShareEntityManager
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareExpanderInterface $resourceShareExpander
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface $resourceShareValidator
     */
    public function __construct(
        ResourceShareEntityManagerInterface $resourceShareEntityManager,
        ResourceShareRepositoryInterface $resourceShareRepository,
        ResourceShareExpanderInterface $resourceShareExpander,
        ResourceShareValidatorInterface $resourceShareValidator
    ) {
        $this->resourceShareEntityManager = $resourceShareEntityManager;
        $this->resourceShareRepository = $resourceShareRepository;
        $this->resourceShareExpander = $resourceShareExpander;
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

        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();

        $existingResourceShareTransfer = $this->findResourceShare($resourceShareTransfer);
        if ($existingResourceShareTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(true)
                ->setResourceShare($existingResourceShareTransfer);
        }

        $resourceShareResponseTransfer = $this->resourceShareValidator->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        return $this->createResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    protected function findResourceShare(ResourceShareTransfer $resourceShareTransfer): ?ResourceShareTransfer
    {
        $existingResourceShareTransfer = null;
        if ($resourceShareTransfer->getUuid()) {
            $existingResourceShareTransfer = $this->resourceShareRepository->findResourceShareByUuid($resourceShareTransfer->getUuid());
            if ($existingResourceShareTransfer) {
                return $existingResourceShareTransfer;
            }
        }

        if ($resourceShareTransfer->getResourceType()
            && $this->isResourceDataPropertyModified($resourceShareTransfer)
            && $resourceShareTransfer->getCustomerReference()
        ) {
            return $this->resourceShareRepository->findResourceShareByResource($resourceShareTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    protected function isResourceDataPropertyModified(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if ($resourceShareTransfer->isPropertyModified(ResourceShareTransfer::RESOURCE_DATA)
            && $resourceShareTransfer->getResourceData() === null
        ) {
            return true;
        }

        return $resourceShareTransfer->getResourceData() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function createResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareTransfer = $this->resourceShareEntityManager->createResourceShare($resourceShareTransfer);

        $resourceShareResponseTransfer = (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);

        return $this->resourceShareExpander->executeResourceDataExpanderStrategyPlugins($resourceShareResponseTransfer);
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
