<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Validator;

use Generated\Shared\Transfer\AclEntityRuleTransfer;

interface AclEntityRuleValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @return void
     */
    public function validate(AclEntityRuleTransfer $aclEntityRuleTransfer): void;
}
