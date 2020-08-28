<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ShopContext\Communication\Provider;

use Generated\Shared\Transfer\ShopContextTransfer;

interface ShopContextProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function provide(): ShopContextTransfer;
}
