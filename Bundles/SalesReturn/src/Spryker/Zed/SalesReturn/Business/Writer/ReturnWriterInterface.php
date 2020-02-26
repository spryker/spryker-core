<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Writer;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface ReturnWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer;
}
