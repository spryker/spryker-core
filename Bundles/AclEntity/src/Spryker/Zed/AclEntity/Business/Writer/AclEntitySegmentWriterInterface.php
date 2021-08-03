<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Writer;

use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentResponseTransfer;

interface AclEntitySegmentWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentResponseTransfer
     */
    public function create(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentResponseTransfer;
}
