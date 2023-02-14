<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Event;

use Generated\Shared\Transfer\CategoryTransfer;

interface ProductCategoryEventTriggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEventsForCategory(CategoryTransfer $categoryTransfer): void;
}
