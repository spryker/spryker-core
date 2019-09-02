<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Plugin\UrlsRestApi;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface;

/**
 * @method \Spryker\Glue\ProductsRestApi\ProductsRestApiFactory getFactory()
 */
class ProductAbstractRestUrlResolverAttributesTransferProviderPlugin extends AbstractPlugin implements RestUrlResolverAttributesTransferProviderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns true if the UrlStorageTransfer::fkResourceProductAbstract is not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourceProductAbstract() !== null;
    }

    /**
     * {@inheritdoc}
     * - Looks up the product abstract in the key-value storage by id given in UrlStorageTransfer.
     * - Returns the ResourceIdentifierTransfer with the type and id of the product abstract.
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
            ->createProductAbstractResourceIdentifierReader()
            ->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);
    }
}
