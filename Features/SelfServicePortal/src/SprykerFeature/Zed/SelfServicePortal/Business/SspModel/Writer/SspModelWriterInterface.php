<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer;

use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;

interface SspModelWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionResponseTransfer
     */
    public function createSspModelCollection(SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): SspModelCollectionResponseTransfer;
}
