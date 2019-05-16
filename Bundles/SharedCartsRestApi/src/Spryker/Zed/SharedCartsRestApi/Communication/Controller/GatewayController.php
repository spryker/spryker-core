<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Communication\Controller;

use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function createAction(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        return $this->getFacade()->create($shareCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function updateAction(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        return $this->getFacade()->update($shareCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function deleteAction(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        return $this->getFacade()->delete($shareCartRequestTransfer);
    }
}
