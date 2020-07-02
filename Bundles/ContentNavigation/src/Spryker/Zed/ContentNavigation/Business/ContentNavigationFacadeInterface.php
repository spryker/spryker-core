<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation\Business;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentNavigationFacadeInterface
{
    /**
     * Specification:
     * - Validates Content Navigation.
     * - Returns ContentValidationResponseTransfer with success status and error messages if validation failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigationTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentNavigationTerm(ContentNavigationTermTransfer $contentNavigationTermTransfer): ContentValidationResponseTransfer;
}
