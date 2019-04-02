<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ResourceShare\Business\ResourceShareBusinessFactory getFactory()
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface getRepository()
 */
class ResourceShareFacade extends AbstractFacade implements ResourceShareFacadeInterface
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
            ->createResourceShareWriter()
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
        // TODO: Implement activateResourceShare() method.
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuid(string $uuid): ResourceShareResponseTransfer
    {
        return $this->getFactory()
            ->createResourceShareReader()
            ->getResourceShareByUuid($uuid);
    }
}
