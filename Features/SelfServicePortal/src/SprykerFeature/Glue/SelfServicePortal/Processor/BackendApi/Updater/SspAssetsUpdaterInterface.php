<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface SspAssetsUpdaterInterface
{
    public function updateSspAsset(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;
}
