<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Reader;

use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;

interface SspModelReaderInterface
{
    public function getSspModelCollection(SspModelCriteriaTransfer $sspModelCriteriaTransfer): SspModelCollectionTransfer;
}
