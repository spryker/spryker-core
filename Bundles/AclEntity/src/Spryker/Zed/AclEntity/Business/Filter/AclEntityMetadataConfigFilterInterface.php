<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Filter;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

interface AclEntityMetadataConfigFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function filter(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer;
}
