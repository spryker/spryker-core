<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\SspAssetAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer;

class AssetAssignmentFileParser extends AbstractAssignmentFileParser implements AssetAssignmentFileParserInterface
{
    /**
     * @var string
     */
    protected const SECTION_ASSIGN = 'Asset to be attached';

    /**
     * @var string
     */
    protected const SECTION_DEASSIGN = 'Asset to be detached';

    /**
     * @var string
     */
    protected const SECTION_PREFIX_ASSET = 'Asset to be';

    public function parse(
        SspAssetAssignmentFileParserRequestTransfer $sspAssetAssignmentFileParserRequestTransfer
    ): SspAssetAssignmentFileParserResponseTransfer {
        $content = $sspAssetAssignmentFileParserRequestTransfer->getContentOrFail();
        $lines = array_map('trim', explode("\n", $content));

        $referencesToAssign = $this->extractSection($lines, static::SECTION_ASSIGN, static::SECTION_PREFIX_ASSET);
        $referencesToDeassign = $this->extractSection($lines, static::SECTION_DEASSIGN, static::SECTION_PREFIX_ASSET);

        return (new SspAssetAssignmentFileParserResponseTransfer())
            ->setReferencesToAssign($referencesToAssign)
            ->setReferencesToDeassign($referencesToDeassign);
    }
}
