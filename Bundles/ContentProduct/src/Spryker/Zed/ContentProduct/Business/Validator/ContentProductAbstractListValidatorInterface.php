<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProduct\Business\Validator;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentProductAbstractListValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractListTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validate(
        ContentProductAbstractListTransfer $contentProductAbstractListTransfer
    ): ContentValidationResponseTransfer;
}
