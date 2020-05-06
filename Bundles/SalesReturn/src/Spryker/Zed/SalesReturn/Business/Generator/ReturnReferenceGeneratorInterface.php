<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Generator;

use Generated\Shared\Transfer\ReturnTransfer;

interface ReturnReferenceGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string
     */
    public function generateReturnReference(ReturnTransfer $returnTransfer): string;
}
