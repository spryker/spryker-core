<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface;

class ResourceShareWriter implements ResourceShareWriterInterface
{
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
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface $resourceShareEntityManager
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface $resourceShareReader
     */
    public function __construct(
        ResourceShareEntityManagerInterface $resourceShareEntityManager,
        ResourceShareReaderInterface $resourceShareReader
    ) {
        $this->resourceShareEntityManager = $resourceShareEntityManager;
        $this->resourceShareReader = $resourceShareReader;
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

        $resourceShareTransfer = $this->resourceShareEntityManager->createResourceShare($resourceShareTransfer);

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
