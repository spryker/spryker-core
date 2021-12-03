<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Persistence;

interface ProductLabelGuiRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     * @param int $idLocale
     *
     * @return array<int, array>
     */
    public function getCategoryNamesGroupedByIdProductAbstract(array $productAbstractIds, int $idLocale): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, int>
     */
    public function getAdditionalRelationsCountIndexedByIdProductAbstract(array $productAbstractIds): array;
}
