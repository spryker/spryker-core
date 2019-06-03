<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

class ResourceShareValidator implements ResourceShareValidatorInterface
{
    protected const GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED = 'resource_share.validation.error.resource_share_is_expired';
    protected const GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED = 'resource_share.generation.error.resource_type_is_not_defined';
    protected const GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'resource_share.generation.error.customer_reference_is_not_defined';

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function validateResourceShareTransfer(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();

        if (!$resourceShareTransfer->getResourceType()) {
            return $resourceShareResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED)
                );
        }

        if (!$resourceShareTransfer->getCustomerReference()) {
            return $resourceShareResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED)
                );
        }

        if ($this->isResourceShareExpired($resourceShareTransfer)) {
            return $resourceShareResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED)
                );
        }

        return $resourceShareResponseTransfer
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    protected function isResourceShareExpired(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if (!$resourceShareTransfer->getExpiryDate()) {
            return false;
        }

        return time() >= strtotime($resourceShareTransfer->getExpiryDate());
    }
}
