<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Event;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryEventTriggerManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerCategoryBeforeDeleteEvent(CategoryTransfer $categoryTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerCategoryAfterDeleteEvent(CategoryTransfer $categoryTransfer): void;
}
