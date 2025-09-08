<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

use Generated\Shared\Transfer\SspAssetAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer;

interface AssetAssignmentFileParserInterface
{
    public function parse(
        SspAssetAssignmentFileParserRequestTransfer $SspAssetAssignmentFileParserRequestTransfer
    ): SspAssetAssignmentFileParserResponseTransfer;
}
