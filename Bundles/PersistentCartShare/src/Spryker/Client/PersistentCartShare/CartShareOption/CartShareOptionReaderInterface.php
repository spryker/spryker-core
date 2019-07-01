<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\CartShareOption;

use Generated\Shared\Transfer\CustomerTransfer;

interface CartShareOptionReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string[][]
     */
    public function getCartShareOptions(?CustomerTransfer $customerTransfer): array;
}
