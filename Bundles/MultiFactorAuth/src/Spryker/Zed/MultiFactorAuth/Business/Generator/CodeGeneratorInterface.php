<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Generator;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;

interface CodeGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function generateCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer;
}
