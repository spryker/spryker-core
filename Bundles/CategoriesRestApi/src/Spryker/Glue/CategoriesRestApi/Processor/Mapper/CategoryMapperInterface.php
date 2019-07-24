<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestCategoryNodesAttributesTransfer;
use Generated\Shared\Transfer\RestCategoryTreesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;

interface CategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\RestCategoryTreesTransfer
     */
    public function mapCategoryTreeToRestCategoryTreesTransfer(array $categoryNodeStorageTransfers): RestCategoryTreesTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestCategoryNodesAttributesTransfer
     */
    public function mapCategoryNodeToRestCategoryNodesTransfer(CategoryNodeStorageTransfer $categoryNodeStorageTransfer): RestCategoryNodesAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param \Generated\Shared\Transfer\ResourceIdentifierTransfer $resourceIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceIdentifierTransfer
     */
    public function mapUrlStorageTransferToResourceIdentifierTransfer(
        UrlStorageTransfer $urlStorageTransfer,
        ResourceIdentifierTransfer $resourceIdentifierTransfer
    ): ResourceIdentifierTransfer;
}
