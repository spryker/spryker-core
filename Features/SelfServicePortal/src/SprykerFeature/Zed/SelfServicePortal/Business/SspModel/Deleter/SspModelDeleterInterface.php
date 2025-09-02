<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Deleter;

use Generated\Shared\Transfer\SspModelCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;

interface SspModelDeleterInterface
{
    public function deleteSspModelCollection(
        SspModelCollectionDeleteCriteriaTransfer $sspModelCollectionDeleteCriteriaTransfer
    ): SspModelCollectionResponseTransfer;
}
