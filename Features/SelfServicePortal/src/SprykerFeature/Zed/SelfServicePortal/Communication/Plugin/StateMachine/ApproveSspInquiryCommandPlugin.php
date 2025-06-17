<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ApproveSspInquiryCommandPlugin extends AbstractPlugin implements CommandPluginInterface
{
 /**
  * {@inheritDoc}
  * - Called when event have specific command assigned.
  * - Delegates to business layer for handling inquiry approval.
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
  *
  * @return void
  */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer): void
    {
        $this->getBusinessFactory()->createSspInquiryApprovalHandler()->handleApproval($stateMachineItemTransfer);
    }
}
