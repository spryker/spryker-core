<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface;
use Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface;
use Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectAppendObserver implements UrlCreatorAfterSaveObserverInterface, UrlUpdaterAfterSaveObserverInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    protected $urlRedirectActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface $urlRedirectActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlRedirectActivatorInterface $urlRedirectActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlRedirectActivator = $urlRedirectActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function handleUrlCreation(UrlTransfer $urlTransfer)
    {
        $this->urlQueryContainer->getConnection()->beginTransaction();

        $this->handleRedirectAppend($urlTransfer);

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return void
     */
    public function handleUrlUpdate(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer)
    {
        if ($urlTransfer->getUrl() === $originalUrlTransfer->getUrl()) {
            return;
        }

        $this->urlQueryContainer->getConnection()->beginTransaction();

        $this->handleRedirectAppend($urlTransfer);

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function handleRedirectAppend(UrlTransfer $urlTransfer)
    {
        $urlRedirectEntity = $this->urlQueryContainer
            ->queryUrlRedirectByIdUrl($urlTransfer->getIdUrl())
            ->findOne();

        if (!$urlRedirectEntity) {
            return;
        }

        $chainRedirectEntities = $this->findUrlRedirectEntitiesByTargetUrl($urlTransfer->getUrl());

        foreach ($chainRedirectEntities as $chainRedirectEntity) {
            $chainRedirectEntity->setToUrl($urlRedirectEntity->getToUrl());
            $chainRedirectEntity->save();

            $this->activateUrlRedirect($chainRedirectEntity->getIdUrlRedirect());
        }
    }

    /**
     * @param string $targetUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect[]
     */
    protected function findUrlRedirectEntitiesByTargetUrl($targetUrl)
    {
        $chainRedirectEntities = $this->urlQueryContainer
            ->queryRedirects()
            ->findByToUrl($targetUrl);

        return $chainRedirectEntities;
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return void
     */
    protected function activateUrlRedirect($idUrlRedirect)
    {
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($idUrlRedirect);

        $this->urlRedirectActivator->activateUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @param string $sourceUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function findUrlRedirectEntityBySourceUrl($sourceUrl)
    {
        return $this->urlQueryContainer
            ->queryUrlRedirectBySourceUrl($sourceUrl)
            ->findOne();
    }

}
