<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface;
use Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectAppendObserver extends AbstractUrlCreatorObserver
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
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    public function update(SpyUrl $urlEntity)
    {
        $this->handleRedirectAppend($urlEntity);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    protected function handleRedirectAppend(SpyUrl $urlEntity)
    {
        $redirectEntity = $urlEntity->getSpyUrlRedirect();

        if (!$redirectEntity) {
            return;
        }

        $chainRedirectEntities = $this->findUrlRedirectEntitiesByTargetUrl($urlEntity->getUrl());

        foreach ($chainRedirectEntities as $chainRedirectEntity) {
            $chainRedirectEntity->setToUrl($redirectEntity->getToUrl());
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

}
