<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface SspAssetsReaderInterface
{
    public function getSspAssetCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    public function getSspAsset(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;
}
