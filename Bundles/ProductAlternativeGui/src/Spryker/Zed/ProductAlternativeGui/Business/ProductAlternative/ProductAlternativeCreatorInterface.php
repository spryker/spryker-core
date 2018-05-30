<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;

interface ProductAlternativeCreatorInterface
{
    /**
     * @param string $searchName
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAlternative(string $searchName): ProductAlternativeResponseTransfer;
}
