<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\ImageSetLocalizer;

interface ImageSetLocalizerInterface
{
    /**
     * Builds flat category image set collection out of form image set array.
     *
     * @param array $imageSetLocalizedArray
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function buildCategoryImageSetCollectionFromLocalizedArray(array $imageSetLocalizedArray): array;

    /**
     * Builds form image set collection array, where image sets are grouped by locale name.
     *
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetCollection
     *
     * @return array
     */
    public function buildLocalizedArrayFromImageSetCollection(array $categoryImageSetCollection): array;
}
