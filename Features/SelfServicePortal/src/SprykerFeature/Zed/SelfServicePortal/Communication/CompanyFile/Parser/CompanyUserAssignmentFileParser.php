<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\CompanyUserAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer;

class CompanyUserAssignmentFileParser extends AbstractAssignmentFileParser implements CompanyUserAssignmentFileParserInterface
{
    /**
     * @var string
     */
    protected const HEADER_COMPANY_USER_TO_BE_ATTACHED = 'Company user to be attached';

    /**
     * @var string
     */
    protected const HEADER_COMPANY_USER_TO_BE_DETACHED = 'Company user to be detached';

    public function parseFile(
        CompanyUserAssignmentFileParserRequestTransfer $companyUserAssignmentFileParserRequestTransfer
    ): CompanyUserAssignmentFileParserResponseTransfer {
        $parsedData = $this->parseAssignmentFile(
            $companyUserAssignmentFileParserRequestTransfer->getFileContentOrFail(),
            static::HEADER_COMPANY_USER_TO_BE_ATTACHED,
            static::HEADER_COMPANY_USER_TO_BE_DETACHED,
        );

        return (new CompanyUserAssignmentFileParserResponseTransfer())
            ->setReferencesToAssign($parsedData[static::KEY_ASSIGN])
            ->setReferencesToDeassign($parsedData[static::KEY_DEASSIGN]);
    }
}
