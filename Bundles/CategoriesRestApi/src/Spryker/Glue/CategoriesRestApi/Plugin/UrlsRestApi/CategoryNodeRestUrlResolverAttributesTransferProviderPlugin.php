<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Plugin\UrlsRestApi;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface;

/**
 * @method \Spryker\Glue\CategoriesRestApi\CategoriesRestApiFactory getFactory()
 */
class CategoryNodeRestUrlResolverAttributesTransferProviderPlugin extends AbstractPlugin implements RestUrlResolverAttributesTransferProviderPluginInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     * - Maps data for RestUrlResolverAttributesTransfer from the UrlStorageTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlResolverAttributesTransfer|null
     */
    public function provideRestUrlResolverAttributesTransferByUrlStorageTransfer(UrlStorageTransfer $urlStorageTransfer): ?RestUrlResolverAttributesTransfer
    {
        return $this->getFactory()
            ->createRestUrlResolverAttributesMapper()
            ->mapUrlStorageTransferToRestUrlResolverAttributesTransfer($urlStorageTransfer, new RestUrlResolverAttributesTransfer());
    }
}
