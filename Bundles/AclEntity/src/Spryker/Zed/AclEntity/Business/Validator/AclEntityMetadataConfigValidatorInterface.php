<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Validator;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

interface AclEntityMetadataConfigValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigInvalidKeyException
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigEntityNotFoundException
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityMetadataConfigParentEntityNotFoundException
     *
     * @return void
     */
    public function validate(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): void;
}
