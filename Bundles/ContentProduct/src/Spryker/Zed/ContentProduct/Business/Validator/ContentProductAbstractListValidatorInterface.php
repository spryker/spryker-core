<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProduct\Business\Validator;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentProductAbstractListValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validate(
        ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
    ): ContentValidationResponseTransfer;
}
