<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

interface ImageSetLocalizerInterface
{
    /**
     * Builds flat category image set collection out of form image set array.
     *
     * @param array $formImageSetCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function buildCategoryImageSetCollection(array $formImageSetCollection): array;

    /**
     * Build form image set collection array, where image sets are grouped by locale name.
     *
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetCollection
     *
     * @return array
     */
    public function buildFormImageSetCollection(array $categoryImageSetCollection): array;
}
