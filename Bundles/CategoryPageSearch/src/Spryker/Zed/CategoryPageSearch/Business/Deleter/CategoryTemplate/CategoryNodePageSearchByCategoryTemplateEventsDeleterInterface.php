<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryTemplate;

interface CategoryNodePageSearchByCategoryTemplateEventsDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void;
}
