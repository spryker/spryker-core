<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
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
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getFactory()
            ->createResourceShareWriter()
            ->generateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(
        ResourceShareRequestTransfer $resourceShareRequestTransfer
    ): ResourceShareResponseTransfer {
        return $this->getFactory()
            ->createResourceShareActivator()
            ->activateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuid(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getFactory()
            ->createResourceShareReader()
            ->getResourceShareByUuid($resourceShareRequestTransfer);
    }
}
