<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;

interface AclEntityEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function createAclEntitySegment(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function updateAclEntitySegment(AclEntitySegmentTransfer $aclEntitySegmentTransfer): AclEntitySegmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleTransfer
     */
    public function createAclEntityRule(AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer): AclEntityRuleTransfer;
}
