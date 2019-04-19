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
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareExpanderInterface
     */
    protected $resourceShareExpander;

    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface
     */
    protected $resourceShareValidator;

    /**
     * @param \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface $resourceShareRepository
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareExpanderInterface $resourceShareExpander
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface $resourceShareValidator
     */
    public function __construct(
        ResourceShareRepositoryInterface $resourceShareRepository,
        ResourceShareExpanderInterface $resourceShareExpander,
        ResourceShareValidatorInterface $resourceShareValidator
    ) {
        $this->resourceShareRepository = $resourceShareRepository;
        $this->resourceShareExpander = $resourceShareExpander;
        $this->resourceShareValidator = $resourceShareValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $existingResourceShareTransfer = $this->resourceShareRepository->findResourceShare($resourceShareTransfer);
        if (!$existingResourceShareTransfer) {
            return (new ResourceShareResponseTransfer())->setIsSuccessful(false)
                ->addErrorMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND)
                );
        }

        $resourceShareResponseTransfer = $this->resourceShareValidator->validateResourceShareTransfer($resourceShareTransfer);
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return $resourceShareResponseTransfer;
        }

        $resourceShareResponseTransfer = $this->resourceShareExpander->executeResourceDataExpanderStrategyPlugins(
            $resourceShareResponseTransfer
        );

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($existingResourceShareTransfer);
    }
}
