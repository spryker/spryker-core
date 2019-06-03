<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Communication\Controller;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShareAction(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getFacade()->generateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShareAction(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getFacade()->activateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuidAction(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getFacade()->getResourceShareByUuid($resourceShareRequestTransfer);
    }
}
