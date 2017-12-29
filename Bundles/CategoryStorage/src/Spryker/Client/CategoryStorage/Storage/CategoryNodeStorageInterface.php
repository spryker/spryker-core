<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Storage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CategoryNodeStorageInterface
{
    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName);
}
