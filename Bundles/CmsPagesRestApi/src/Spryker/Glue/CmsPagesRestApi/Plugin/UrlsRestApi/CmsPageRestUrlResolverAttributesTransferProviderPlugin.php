<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Plugin\UrlsRestApi;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface;

/**
 * @method \Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiFactory getFactory()
 */
class CmsPageRestUrlResolverAttributesTransferProviderPlugin extends AbstractPlugin implements RestUrlResolverAttributesTransferProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the UrlStorageTransfer::fkResourcePage is not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourcePage() !== null;
    }

    /**
     * {@inheritDoc}
     * - Looks up the CMS page in the key-value storage by id given in `UrlStorageTransfer`.
     * - Returns the `RestUrlResolverAttributesTransfer` with type "cms-pages" and CMS page UUID as entity ID.
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
            ->createCmsPageUrlResolver()
            ->resolveCmsPagetUrl($urlStorageTransfer);
    }
}
