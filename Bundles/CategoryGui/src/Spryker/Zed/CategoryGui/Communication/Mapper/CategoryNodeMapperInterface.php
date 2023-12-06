<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Mapper;

use Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer;

interface CategoryNodeMapperInterface
{
    /**
     * @param list<array<string, mixed>> $categoryNodesData
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer
     */
    public function mapCategoryNodesDataToCategoryNodeCollectionRequestTransfer(
        array $categoryNodesData,
        CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
    ): CategoryNodeCollectionRequestTransfer;
}
