<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Facade;

interface DatasetToTouchFacadeInterface
{
    /**
     * @param string $itemType
     * @param int $idDataset
     *
     * @return bool
     */
    public function touchActive($itemType, $idDataset);

    /**
     * @param string $itemType
     * @param int $idDataset
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idDataset);
}
