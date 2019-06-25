<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFile\Business;

use Generated\Shared\Transfer\ContentFileListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentFileFacadeInterface
{
    /**
     * Specification:
     * - Validates data in ContentFileListTermTransfer.
     * - Returns ContentValidationResponseTransfer with a success status, or error messages if validation failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentFileListTermTransfer $contentFileListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentFileListTerm(
        ContentFileListTermTransfer $contentFileListTermTransfer
    ): ContentValidationResponseTransfer;
}
