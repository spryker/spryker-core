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

class ResourceShareWriter implements ResourceShareWriterInterface
{
    protected const GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED = 'resource_share.generation.error.resource_type_is_not_defined';
    protected const GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'resource_share.generation.error.customer_reference_is_not_defined';
    protected const GLOSSARY_KEY_RESOURCE_IS_ALREADY_SHARED = 'resource_share.generation.error.resource_is_already_shared';

    /**
     * @var \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface
     */
    protected $resourceShareEntityManager;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    protected $resourceShareReader;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareExpanderInterface
     */
    protected $resourceShareExpander;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface $resourceShareEntityManager
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface $resourceShareReader
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareExpanderInterface $resourceShareExpander
     */
    public function __construct(
        ResourceShareEntityManagerInterface $resourceShareEntityManager,
        ResourceShareReaderInterface $resourceShareReader,
        ResourceShareExpanderInterface $resourceShareExpander
    ) {
        $this->resourceShareEntityManager = $resourceShareEntityManager;
        $this->resourceShareReader = $resourceShareReader;
        $this->resourceShareExpander = $resourceShareExpander;
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

        $resourceShareResponseTransfer = $this->resourceShareReader->getResourceShare($resourceShareTransfer);
        if ($resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addErrorMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_ALREADY_SHARED)
                );
        }

        return $this->createResourceShare($resourceShareTransfer);
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
