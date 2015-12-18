<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Url\Business\Exception\MissingRedirectException;
use Spryker\Zed\Url\Business\Exception\RedirectExistsException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Spryker\Zed\Url\Dependency\UrlToTouchInterface;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class RedirectManager implements RedirectManagerInterface
{

    const ITEM_TYPE_REDIRECT = 'redirect';

    /**
     * @var UrlManagerInterface
     */
    protected $urlManager;

    /**
     * @var UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param UrlQueryContainerInterface $urlQueryContainer
     * @param UrlManagerInterface $urlManager
     * @param UrlToTouchInterface $touchFacade
     * @param ConnectionInterface $connection
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlManagerInterface $urlManager,
        UrlToTouchInterface $touchFacade,
        ConnectionInterface $connection
    ) {
        $this->urlManager = $urlManager;
        $this->urlQueryContainer = $urlQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->connection = $connection;
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @throws RedirectExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return SpyUrlRedirect
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
     * @param string $toUrl
     * @param int $status
     *
     * @return RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 301)
    {
        $redirect = $this->createRedirect($toUrl, $status);

        $redirectTransfer = $this->convertRedirectEntityToTransfer($redirect);
        $this->touchRedirectActive($redirectTransfer);

        return $redirectTransfer;
    }

    /**
     * @param SpyUrlRedirect $redirectEntity
     *
     * @return RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyUrlRedirect $redirectEntity)
    {
        $transferRedirect = new RedirectTransfer();
        $transferRedirect->fromArray($redirectEntity->toArray());

        return $transferRedirect;
    }

    /**
     * @param RedirectTransfer $redirect
     *
     * @throws RedirectExistsException
     *
     * @return RedirectTransfer
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
     * @param RedirectTransfer $redirect
     *
     * @return RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect)
    {
        $redirectTransfer = $this->saveRedirect($redirect);
        $this->touchRedirectActive($redirectTransfer);

        return $redirectTransfer;
    }

    /**
     * @param RedirectTransfer $redirectTransfer
     *
     * @throws RedirectExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return RedirectTransfer
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
     * @param RedirectTransfer $redirectTransfer
     *
     * @throws MissingRedirectException
     * @throws \Exception
     * @throws PropelException
     *
     * @return RedirectTransfer
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
     * @throws MissingRedirectException
     *
     * @return SpyUrlRedirect
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
     * @param RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect)
    {
        $this->touchFacade->touchActive(self::ITEM_TYPE_REDIRECT, $redirect->getIdUrlRedirect());
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @throws UrlExistsException
     * @throws MissingLocaleException
     * @throws MissingRedirectException
     *
     * @return UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        $this->checkRedirectExists($idUrlRedirect);
        $urlEntity = $this->urlManager->createUrl($url, $locale, 'redirect', $idUrlRedirect);

        return $this->urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return UrlTransfer
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
     * @throws MissingRedirectException
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
