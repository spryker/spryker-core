<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
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
     */
    public function findCategoryImageSetCollectionStorage(int $idCategory, string $localeName): ?CategoryImageSetCollectionStorageTransfer
    {
        return $this->getFactory()
            ->createCategoryImageStorageReader()
            ->findCategoryImageStorage($idCategory, $localeName);
    }
}
