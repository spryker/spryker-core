<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Deletion;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Spryker\Zed\Url\Business\Exception\MissingRedirectException;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface;
use Spryker\Zed\Url\Business\Url\UrlActivatorInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlDeleter implements UrlDeleterInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlActivatorInterface
     */
    protected $urlActivator;

    /**
     * @var \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    protected $urlRedirectActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Url\UrlActivatorInterface $urlActivator
     * @param \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface $urlRedirectActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlActivatorInterface $urlActivator, UrlRedirectActivatorInterface $urlRedirectActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlActivator = $urlActivator;
        $this->urlRedirectActivator = $urlRedirectActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        $urlEntity = $this->getUrlEntityToDelete($urlTransfer);

        $this->urlQueryContainer->getConnection()->beginTransaction();

        $this->deleteUrlEntity($urlEntity);

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectEntity = $this->getRedirectEntity($urlRedirectTransfer);

        $this->urlQueryContainer->getConnection()->beginTransaction();

        $this->deleteUrlRedirectEntity($urlRedirectEntity);

        $this->urlQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl|null
     */
    protected function getUrlEntityToDelete(UrlTransfer $urlTransfer)
    {
        $urlTransfer->requireIdUrl();

        $urlEntity = $this->urlQueryContainer
            ->queryUrlById($urlTransfer->getIdUrl())
            ->findOne();

        if (!$urlEntity) {
            throw new MissingUrlException(sprintf(
                'Tried to retrieve a missing url with id %s',
                $urlTransfer->getIdUrl()
            ));
        }

        return $urlEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingRedirectException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function getRedirectEntity(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectTransfer->requireIdUrlRedirect();

        $redirectEntity = $this->urlQueryContainer
            ->queryRedirectById($urlRedirectTransfer->getIdUrlRedirect())
            ->findOne();

        if (!$redirectEntity) {
            throw new MissingRedirectException(sprintf(
                'Tried to retrieve a missing redirect with id %s',
                $urlRedirectTransfer->getIdUrlRedirect()
            ));
        }

        return $redirectEntity;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    protected function deleteUrlEntity(SpyUrl $urlEntity)
    {
        $urlEntity->delete();

        $this->deactivateUrl($urlEntity->getIdUrl());

        $this->deleteRedirectedUrls($urlEntity);
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    protected function deactivateUrl($idUrl)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($idUrl);

        $this->urlActivator->deactivateUrl($urlTransfer);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    protected function deleteRedirectedUrls(SpyUrl $urlEntity)
    {
        $urlRedirectsToDelete = $this->urlQueryContainer
            ->queryRedirects()
            ->findByToUrl($urlEntity->getUrl());

        foreach ($urlRedirectsToDelete as $urlRedirectEntity) {
            $this->deleteUrlRedirectEntity($urlRedirectEntity);
        }
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlRedirect $urlRedirectEntity
     *
     * @return void
     */
    protected function deleteUrlRedirectEntity(SpyUrlRedirect $urlRedirectEntity)
    {
        foreach ($urlRedirectEntity->getSpyUrls() as $urlEntity) {
            $this->deleteUrlEntity($urlEntity);
        }

        $urlRedirectEntity->delete();

        $this->deactivateUrlRedirect($urlRedirectEntity->getIdUrlRedirect());
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return void
     */
    protected function deactivateUrlRedirect($idUrlRedirect)
    {
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($idUrlRedirect);

        $this->urlRedirectActivator->deactivateUrlRedirect($urlRedirectTransfer);
    }

}
