<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Facade;

interface DataImportToTouchInterface
{
    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return void
     */
    public function bulkTouchSetActive($itemType, array $itemIds);

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return void
     */
    public function bulkTouchSetInactive($itemType, array $itemIds);

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return void
     */
    public function bulkTouchSetDeleted($itemType, array $itemIds);
}
