<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation\Business\Validator;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentNavigationValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigationTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentNavigationTerm(ContentNavigationTermTransfer $contentNavigationTermTransfer): ContentValidationResponseTransfer;
}
