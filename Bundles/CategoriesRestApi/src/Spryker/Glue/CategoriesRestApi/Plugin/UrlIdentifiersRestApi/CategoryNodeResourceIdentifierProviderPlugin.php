<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Plugin\UrlIdentifiersRestApi;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface;

/**
 * @method \Spryker\Glue\CategoriesRestApi\CategoriesRestApiFactory getFactory()
 */
class CategoryNodeResourceIdentifierProviderPlugin extends AbstractPlugin implements ResourceIdentifierProviderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns true if the UrlStorageTransfer::fkResourceCategorynode is not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourceCategorynode() !== null;
    }

    /**
     * {@inheritdoc}
     * - Creates ResourceIdentifierTransfer from the data in UrlStorageTransfer.
     * - Returns ResourceIdentifierTransfer on success, null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceIdentifierTransfer|null
     */
    public function provideResourceIdentifierByUrlStorageTransfer(UrlStorageTransfer $urlStorageTransfer): ?ResourceIdentifierTransfer
    {
        return $this->getFactory()
            ->createCategoryMapper()
            ->mapUrlStorageTransferToResourceIdentifierTransfer($urlStorageTransfer, new ResourceIdentifierTransfer());
    }
}
