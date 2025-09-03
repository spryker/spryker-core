<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\CompanyUserAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer;

interface CompanyUserAssignmentFileParserInterface
{
    public function parseFile(
        CompanyUserAssignmentFileParserRequestTransfer $companyUserAssignmentFileParserRequestTransfer
    ): CompanyUserAssignmentFileParserResponseTransfer;
}
