<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Reader;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

interface AclEntityMetadataConfigReaderInterface
{
    /**
     * @param bool $runValidation
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(bool $runValidation): AclEntityMetadataConfigTransfer;
}
