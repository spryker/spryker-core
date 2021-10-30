<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentResponseTransfer;

class AclMerchantPortalToAclEntityFacadeBridge implements AclMerchantPortalToAclEntityFacadeInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface
     */
    protected $aclEntityFacade;

    /**
     * @param \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface $aclEntityFacade
     */
    public function __construct($aclEntityFacade)
    {
        $this->aclEntityFacade = $aclEntityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentResponseTransfer
     */
    public function createAclEntitySegment(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentResponseTransfer
    {
        return $this->aclEntityFacade->createAclEntitySegment($aclEntitySegmentRequestTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return void
     */
    public function saveAclEntityRules(ArrayObject $aclEntityRuleTransfers): void
    {
        $this->aclEntityFacade->saveAclEntityRules($aclEntityRuleTransfers);
    }
}
