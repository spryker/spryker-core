<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CategoryImageStorage\CategoryImageStorageFactory getFactory()
 */
class CategoryImageStorageClient extends AbstractClient implements CategoryImageStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use `findCategoryImageStorageItem()` instead.
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer|null
     */
    public function findCategoryImageSetCollectionStorage(int $idCategory, string $localeName): ?CategoryImageSetCollectionStorageTransfer
    {
        return $this->getFactory()
            ->createCategoryImageStorageReader()
            ->findCategoryImageStorage($idCategory, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer|null
     */
    public function findCategoryImageStorageItemData(int $idCategory, string $localeName): ?CategoryImageStorageItemDataTransfer
    {
        return $this->getFactory()
            ->createCategoryImageStorageReader()
            ->findCategoryImageStorageItemData($idCategory, $localeName);
    }
}
