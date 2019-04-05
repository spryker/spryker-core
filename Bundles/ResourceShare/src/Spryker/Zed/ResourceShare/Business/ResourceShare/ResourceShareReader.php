<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface;

class ResourceShareReader implements ResourceShareReaderInterface
{
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.reader.error.resource_is_not_found';

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
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuid(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer->requireUuid();
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();

        $resourceShareTransfer = $this->resourceShareRepository->findResourceShareByUuid(
            $resourceShareRequestTransfer->getUuid()
        );

        if ($resourceShareTransfer) {
            return $resourceShareResponseTransfer->setIsSuccessful(true)
                ->setResourceShare($resourceShareTransfer);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(false)
            ->addErrorMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID)
            );
    }
}
