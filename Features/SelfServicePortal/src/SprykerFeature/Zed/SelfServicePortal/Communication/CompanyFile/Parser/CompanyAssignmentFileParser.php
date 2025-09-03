<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\CompanyAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer;

class CompanyAssignmentFileParser extends AbstractAssignmentFileParser implements CompanyAssignmentFileParserInterface
{
    /**
     * @var string
     */
    protected const HEADER_COMPANY_TO_BE_ATTACHED = 'Company to be attached';

    /**
     * @var string
     */
    protected const HEADER_COMPANY_TO_BE_DETACHED = 'Company to be detached';

    public function parseFile(
        CompanyAssignmentFileParserRequestTransfer $companyAssignmentFileParserRequestTransfer
    ): CompanyAssignmentFileParserResponseTransfer {
        $parsedData = $this->parseAssignmentFile(
            $companyAssignmentFileParserRequestTransfer->getFileContentOrFail(),
            static::HEADER_COMPANY_TO_BE_ATTACHED,
            static::HEADER_COMPANY_TO_BE_DETACHED,
        );

        return (new CompanyAssignmentFileParserResponseTransfer())
            ->setReferencesToAssign($parsedData[static::KEY_ASSIGN])
            ->setReferencesToDeassign($parsedData[static::KEY_DEASSIGN]);
    }
}
