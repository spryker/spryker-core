<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer;
use Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer;

interface ComposerJsonInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer $composerJsonValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer
     */
    public function validate(ComposerJsonValidationRequestTransfer $composerJsonValidationRequestTransfer): ComposerJsonValidationResponseTransfer;
}
