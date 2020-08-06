<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Plugin\UrlsRestApi;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface;

/**
 * @method \Spryker\Glue\MerchantsRestApi\MerchantsRestApiFactory getFactory()
 */
class MerchantRestUrlResolverAttributesTransferProviderPlugin extends AbstractPlugin implements RestUrlResolverAttributesTransferProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the `UrlStorageTransfer::fkResourceMerchant` is not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourceMerchant() !== null;
    }

    /**
     * {@inheritDoc}
     * - Looks up the merchant in the key-value storage by id given in `UrlStorageTransfer`.
     * - Returns the `RestUrlResolverAttributesTransfer` with type "merchant" and merchant reference as ID.
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
            ->createMerchantUrlResolver()
            ->resolveMerchantUrl($urlStorageTransfer);
    }
}
