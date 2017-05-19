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
     * @param int $itemId
     * @param bool $keyChange
     *
     * @return void
     */
    public function touchActive($itemType, $itemId, $keyChange = false);

}
