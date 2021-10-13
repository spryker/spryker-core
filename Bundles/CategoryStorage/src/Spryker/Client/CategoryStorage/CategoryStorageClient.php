<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 */
class CategoryStorageClient extends AbstractClient implements CategoryStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $localeName
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategories(string $localeName, string $storeName): ArrayObject
    {
        return $this->getFactory()
            ->createCategoryTreeStorageReader()
            ->getCategories($localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeById($idCategoryNode, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeByIds($categoryNodeIds, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $docCountAggregation
     * @param string $localeName
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    public function formatCategoryTreeFilter(array $docCountAggregation, string $localeName, string $storeName): ArrayObject
    {
        return $this->getFactory()
            ->createCategoryTreeFilterFormatter()
            ->formatCategoryTreeFilter($docCountAggregation, $localeName, $storeName);
    }
}
