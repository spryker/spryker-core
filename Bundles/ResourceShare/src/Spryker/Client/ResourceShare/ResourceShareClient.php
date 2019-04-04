<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ResourceShare\ResourceShareFactory getFactory()
 */
class ResourceShareClient extends AbstractClient implements ResourceShareClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        return $this->getFactory()
            ->createZedResourceShareStub()
            ->generateResourceShare($resourceShareTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $uuid
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(
        string $uuid,
        ?CustomerTransfer $customerTransfer
    ): ResourceShareResponseTransfer {
        return $this->getFactory()
            ->createResourceShareActivator()
            ->activateResourceShare($uuid, $customerTransfer);
    }
}
