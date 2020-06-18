<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\UrlResolver;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface;

class CmsPageUrlResolver implements CmsPageUrlResolverInterface
{
    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface
     */
    protected $cmsStoreClient;

    /**
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface $cmsStoreClient
     */
    public function __construct(
        CmsPagesRestApiToCmsStorageClientInterface $cmsStorageClient,
        CmsPagesRestApiToStoreClientInterface $cmsStoreClient
    ) {
        $this->cmsStorageClient = $cmsStorageClient;
        $this->cmsStoreClient = $cmsStoreClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlResolverAttributesTransfer|null
     */
    public function resolveCmsPagetUrl(UrlStorageTransfer $urlStorageTransfer): ?RestUrlResolverAttributesTransfer
    {
        $urlStorageTransfer->requireFkResourcePage();

        $localeName = $this->findLocaleName($urlStorageTransfer);
        if (!$localeName) {
            return null;
        }

        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByIds(
            [$urlStorageTransfer->getFkResourcePage()],
            $localeName,
            $this->cmsStoreClient->getCurrentStore()->getName()
        );

        $cmsPageStorageTransfer = reset($cmsPageStorageTransfers);
        if (!$cmsPageStorageTransfer) {
            return null;
        }

        return (new RestUrlResolverAttributesTransfer())
            ->setEntityId($cmsPageStorageTransfer->getUuid())
            ->setEntityType(CmsPagesRestApiConfig::RESOURCE_CMS_PAGES);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return string|null
     */
    protected function findLocaleName(UrlStorageTransfer $urlStorageTransfer): ?string
    {
        if ($urlStorageTransfer->getLocaleName()) {
            return $urlStorageTransfer->getLocaleName();
        }

        foreach ($urlStorageTransfer->getLocaleUrls() as $localeUrlStorageTransfer) {
            if ($localeUrlStorageTransfer->getFkLocale() !== $urlStorageTransfer->getFkLocale()) {
                continue;
            }

            return $localeUrlStorageTransfer->getLocaleName();
        }

        return null;
    }
}
