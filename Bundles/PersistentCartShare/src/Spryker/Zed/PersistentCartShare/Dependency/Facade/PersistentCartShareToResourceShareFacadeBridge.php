<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Dependency\Facade;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

class PersistentCartShareToResourceShareFacadeBridge implements PersistentCartShareToResourceShareFacadeInterface
{
    /**
     * @var \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface
     */
    protected $resourceShareFacade;

    /**
     * @param \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface $resourceShareFacade
     */
    public function __construct($resourceShareFacade)
    {
        $this->resourceShareFacade = $resourceShareFacade;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        return $this->resourceShareFacade->getResourceShare($resourceShareTransfer);
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
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->resourceShareFacade->activateResourceShare($resourceShareRequestTransfer);
    }
}
