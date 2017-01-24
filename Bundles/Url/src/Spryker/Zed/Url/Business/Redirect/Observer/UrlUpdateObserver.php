<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectCreatorInterface;
use Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterObserver;
use Symfony\Component\HttpFoundation\Response;

class UrlUpdateObserver extends AbstractUrlUpdaterObserver
{

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectCreatorInterface
     */
    protected $urlRedirectCreator;

    /**
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectCreatorInterface $urlRedirectWriter
     */
    public function __construct(UrlRedirectCreatorInterface $urlRedirectWriter)
    {
        $this->urlRedirectCreator = $urlRedirectWriter;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl $originalUrlEntity
     *
     * @return void
     */
    public function update(SpyUrl $urlEntity, SpyUrl $originalUrlEntity)
    {
        if ($originalUrlEntity->getUrl() === $urlEntity->getUrl()) {
            return;
        }

        $this->redirectOriginalUrlToUpdatedUrl($urlEntity, $originalUrlEntity);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl $originalUrlEntity
     *
     * @return void
     */
    protected function redirectOriginalUrlToUpdatedUrl(SpyUrl $urlEntity, SpyUrl $originalUrlEntity)
    {
        $urlRedirectTransfer = $this->createUrlRedirectTransfer($urlEntity, $originalUrlEntity);

        $this->urlRedirectCreator->createUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl $originalUrlEntity
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    protected function createUrlRedirectTransfer(SpyUrl $urlEntity, SpyUrl $originalUrlEntity)
    {
        $sourceUrlTransfer = $this->createUrlTransfer($originalUrlEntity);

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl($urlEntity->getUrl())
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY);

        return $urlRedirectTransfer;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $originalUrlEntity
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(SpyUrl $originalUrlEntity)
    {
        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setUrl($originalUrlEntity->getUrl())
            ->setFkLocale($originalUrlEntity->getFkLocale());

        return $sourceUrlTransfer;
    }

}
