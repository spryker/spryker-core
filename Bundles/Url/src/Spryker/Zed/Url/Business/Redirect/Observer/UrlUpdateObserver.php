<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectCreatorInterface;
use Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface;
use Symfony\Component\HttpFoundation\Response;

class UrlUpdateObserver implements UrlUpdaterAfterSaveObserverInterface
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return void
     */
    public function handleUrlUpdate(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer)
    {
        if ($originalUrlTransfer->getUrl() === $urlTransfer->getUrl()) {
            return;
        }

        $this->redirectOriginalUrlToUpdatedUrl($urlTransfer, $originalUrlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return void
     */
    protected function redirectOriginalUrlToUpdatedUrl(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer)
    {
        $urlRedirectTransfer = $this->createUrlRedirectTransfer($urlTransfer, $originalUrlTransfer);

        $this->urlRedirectCreator->createUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    protected function createUrlRedirectTransfer(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer)
    {
        $sourceUrlTransfer = $this->createUrlTransfer($originalUrlTransfer);

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl($urlTransfer->getUrl())
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY);

        return $urlRedirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(UrlTransfer $originalUrlTransfer)
    {
        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setUrl($originalUrlTransfer->getUrl())
            ->setFkLocale($originalUrlTransfer->getFkLocale());

        return $sourceUrlTransfer;
    }
}
