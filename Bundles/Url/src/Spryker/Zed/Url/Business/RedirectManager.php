<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Url\Business\Exception\MissingRedirectException;
use Spryker\Zed\Url\Dependency\UrlToTouchInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @deprecated Use business classes from Spryker\Zed\Url\Business\Redirect namespace.
 */
class RedirectManager implements RedirectManagerInterface
{
    /**
     * @var string
     */
    public const ITEM_TYPE_REDIRECT = 'redirect';

    /**
     * @var \Spryker\Zed\Url\Business\UrlManagerInterface
     */
    protected $urlManager;

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Dependency\UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @deprecated Use business classes from Spryker\Zed\Url\Business\Url namespace.
     *
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\UrlManagerInterface $urlManager
     * @param \Spryker\Zed\Url\Dependency\UrlToTouchInterface $touchFacade
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlManagerInterface $urlManager,
        UrlToTouchInterface $touchFacade,
        ConnectionInterface $connection
    ) {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlManager = $urlManager;
        $this->touchFacade = $touchFacade;
        $this->connection = $connection;
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    public function createRedirect($toUrl, $status = 301)
    {
        $this->connection->beginTransaction();

        $redirect = new SpyUrlRedirect();

        $redirect
            ->setToUrl($toUrl)
            ->setStatus($status)
            ->save();

        $this->connection->commit();

        return $redirect;
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect(RedirectTransfer $redirectTransfer)
    {
        $redirectEntity = $this->getRedirectById($redirectTransfer->getIdUrlRedirect());
        $this->touchDeleted($redirectTransfer);

        $redirectEntity->delete();
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 301)
    {
        $redirect = $this->createRedirect($toUrl, $status);

        $redirectTransfer = $this->convertRedirectEntityToTransfer($redirect);
        $this->touchRedirectActive($redirectTransfer);

        return $redirectTransfer;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlRedirect $redirectEntity
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyUrlRedirect $redirectEntity)
    {
        $transferRedirect = new RedirectTransfer();
        $transferRedirect->fromArray($redirectEntity->toArray());

        return $transferRedirect;
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect)
    {
        if ($redirect->getIdUrlRedirect() === null) {
            return $this->createRedirectFromTransfer($redirect);
        } else {
            return $this->updateRedirectFromTransfer($redirect);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect)
    {
        $redirectTransfer = $this->saveRedirect($redirect);
        $this->touchRedirectActive($redirectTransfer);

        return $redirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    protected function createRedirectFromTransfer(RedirectTransfer $redirectTransfer)
    {
        $redirectEntity = new SpyUrlRedirect();

        $this->connection->beginTransaction();

        $redirectEntity->fromArray($redirectTransfer->toArray());

        $redirectEntity->save();
        $this->connection->commit();

        $redirectTransfer->setIdUrlRedirect($redirectEntity->getIdUrlRedirect());

        return $redirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    protected function updateRedirectFromTransfer(RedirectTransfer $redirectTransfer)
    {
        $redirectEntity = $this->getRedirectById($redirectTransfer->getIdUrlRedirect());
        $redirectEntity->fromArray($redirectTransfer->toArray());

        if (!$redirectEntity->isModified()) {
            return $redirectTransfer;
        }

        $redirectEntity->save();

        return $redirectTransfer;
    }

    /**
     * @param int $idUrlRedirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingRedirectException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function getRedirectById($idUrlRedirect)
    {
        $redirect = $this->urlQueryContainer->queryRedirectById($idUrlRedirect)->findOne();
        if (!$redirect) {
            throw new MissingRedirectException(
                sprintf(
                    'Tried to retrieve a missing redirect with id %s',
                    $idUrlRedirect
                )
            );
        }

        return $redirect;
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect)
    {
        $this->touchFacade->touchActive(self::ITEM_TYPE_REDIRECT, $redirect->getIdUrlRedirect());
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return void
     */
    protected function touchDeleted(RedirectTransfer $redirectTransfer)
    {
        $this->touchFacade->touchDeleted(self::ITEM_TYPE_REDIRECT, $redirectTransfer->getIdUrlRedirect());
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        $this->checkRedirectExists($idUrlRedirect);
        $urlEntity = $this->urlManager->createUrl($url, $locale, 'redirect', $idUrlRedirect);

        return $this->urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        $urlTransfer = $this->createRedirectUrl($url, $locale, $idUrlRedirect);
        $this->urlManager->touchUrlActive($urlTransfer->getIdUrl());

        return $urlTransfer;
    }

    /**
     * @param int $idUrlRedirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingRedirectException
     *
     * @return void
     */
    protected function checkRedirectExists($idUrlRedirect)
    {
        if (!$this->hasRedirectId($idUrlRedirect)) {
            throw new MissingRedirectException();
        }
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return bool
     */
    protected function hasRedirectId($idUrlRedirect)
    {
        $query = $this->urlQueryContainer->queryRedirectById($idUrlRedirect);

        return $query->count() > 0;
    }
}
