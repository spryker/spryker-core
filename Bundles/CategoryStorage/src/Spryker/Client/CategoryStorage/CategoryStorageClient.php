<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use ArrayObject;
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
     * @param string $locale
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale, ?string $storeName = null)
    {
        if ($storeName === null) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createCategoryTreeStorageReader()
            ->getCategories($locale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName)
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeById($idCategoryNode, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName): array
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeByIds($categoryNodeIds, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $docCountAggregation
     * @param string|null $localeName
     * @param string|null $storeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    public function formatCategoryTreeFilter(array $docCountAggregation, ?string $localeName = null, ?string $storeName = null): ArrayObject
    {
        if ($localeName === null) {
            trigger_error('Pass the $localeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        if ($storeName === null) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createCategoryTreeFilterFormatter()
            ->formatCategoryTreeFilter($docCountAggregation);
    }
}
