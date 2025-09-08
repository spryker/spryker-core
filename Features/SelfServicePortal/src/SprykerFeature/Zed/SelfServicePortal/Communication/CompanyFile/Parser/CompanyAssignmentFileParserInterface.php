<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\CompanyAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer;

interface CompanyAssignmentFileParserInterface
{
    public function parseFile(
        CompanyAssignmentFileParserRequestTransfer $companyAssignmentFileParserRequestTransfer
    ): CompanyAssignmentFileParserResponseTransfer;
}
