<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;

class ResourceShareWriter implements ResourceShareWriterInterface
{
    protected const GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED = 'resource_share.generation.error.resource_type_is_not_defined';
    protected const GLOSSARY_KEY_RESOURCE_DATA_IS_NOT_DEFINED = 'resource_share.generation.error.resource_data_is_not_defined';
    protected const GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'resource_share.generation.error.customer_reference_is_not_defined';
    protected const GLOSSARY_KEY_RESOURCE_IS_ALREADY_SHARED = 'resource_share.generation.error.resource_is_already_shared';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface
     */
    protected $resourceShareEntityManager;

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface
     */
    protected $resourceShareRepository;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface $resourceShareEntityManager
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     */
    public function __construct(
        ResourceShareEntityManagerInterface $resourceShareEntityManager,
        ResourceShareRepositoryInterface $resourceShareRepository
    ) {
        $this->resourceShareEntityManager = $resourceShareEntityManager;
        $this->resourceShareRepository = $resourceShareRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = $this->preValidateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $existingResourceShareTransfer = $this->resourceShareRepository->findExistingResourceShareForProvidedCustomer($resourceShareTransfer);
        if ($existingResourceShareTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addErrorMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_ALREADY_SHARED)
                );
        }

        $resourceShareTransfer = $this->resourceShareEntityManager->createResourceShare($resourceShareTransfer);

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function preValidateResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
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
