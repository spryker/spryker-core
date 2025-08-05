<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer;

use Generated\Shared\Transfer\SspModelTransfer;

interface FileSspModelWriterInterface
{
    public function createFile(SspModelTransfer $sspModelTransfer): SspModelTransfer;
}
