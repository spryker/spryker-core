<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Publisher;

interface CategoryNodePublisherInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function triggerBulkCategoryNodePublishEventForCreate(int $idCategoryNode): void;

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function triggerBulkCategoryNodePublishEventForUpdate(int $idCategoryNode): void;
}
