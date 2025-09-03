<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\BusinessUnitAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer;

class BusinessUnitAssignmentFileParser extends AbstractAssignmentFileParser implements BusinessUnitAssignmentFileParserInterface
{
    /**
     * @var string
     */
    protected const SECTION_ASSIGN = 'Business unit to be attached';

    /**
     * @var string
     */
    protected const SECTION_DEASSIGN = 'Business unit to be detached';

    /**
     * @var string
     */
    protected const SECTION_PREFIX_BUSINESS_UNIT = 'Business unit to be';

    public function parse(BusinessUnitAssignmentFileParserRequestTransfer $requestTransfer): BusinessUnitAssignmentFileParserResponseTransfer
    {
        $content = $requestTransfer->getContentOrFail();
        $lines = array_map('trim', explode("\n", $content));

        $referencesToAssign = $this->extractSection($lines, static::SECTION_ASSIGN, static::SECTION_PREFIX_BUSINESS_UNIT);
        $referencesToDeassign = $this->extractSection($lines, static::SECTION_DEASSIGN, static::SECTION_PREFIX_BUSINESS_UNIT);

        return (new BusinessUnitAssignmentFileParserResponseTransfer())
            ->setReferencesToAssign($referencesToAssign)
            ->setReferencesToDeassign($referencesToDeassign);
    }
}
