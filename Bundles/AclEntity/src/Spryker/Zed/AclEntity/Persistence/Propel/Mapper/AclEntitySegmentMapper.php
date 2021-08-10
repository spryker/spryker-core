<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment;

class AclEntitySegmentMapper
{
    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment $spyAclEntitySegment
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment
     */
    public function mapAclEntitySegmentRequestTransferToEntity(
        AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer,
        SpyAclEntitySegment $spyAclEntitySegment
    ): SpyAclEntitySegment {
        $spyAclEntitySegment->fromArray($aclEntitySegmentRequestTransfer->toArray(false));

        return $spyAclEntitySegment;
    }

    /**
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment $aclEntitySegmentEntity
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function mapAclEntitySegmentEntityToTransfer(
        SpyAclEntitySegment $aclEntitySegmentEntity,
        AclEntitySegmentTransfer $aclEntitySegmentTransfer
    ): AclEntitySegmentTransfer {
        return $aclEntitySegmentTransfer->fromArray($aclEntitySegmentEntity->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment $spyAclEntitySegment
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment
     */
    public function mapAclEntitySegmentTransferToEntity(
        AclEntitySegmentTransfer $aclEntitySegmentTransfer,
        SpyAclEntitySegment $spyAclEntitySegment
    ): SpyAclEntitySegment {
        $spyAclEntitySegment->fromArray($aclEntitySegmentTransfer->toArray(false));

        return $spyAclEntitySegment;
    }
}
