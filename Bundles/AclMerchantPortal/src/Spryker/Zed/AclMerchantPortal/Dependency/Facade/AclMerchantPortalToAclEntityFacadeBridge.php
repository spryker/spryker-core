<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCollectionTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentCollectionTransfer
     */
    public function getAclEntitySegmentCollection(
        AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
    ): AclEntitySegmentCollectionTransfer {
        return $this->aclEntityFacade->getAclEntitySegmentCollection($aclEntitySegmentCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRuleCollection(
        AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
    ): AclEntityRuleCollectionTransfer {
        return $this->aclEntityFacade->getAclEntityRuleCollection($aclEntityRuleCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentResponseTransfer
     */
    public function createAclEntitySegment(
        AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
    ): AclEntitySegmentResponseTransfer {
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
