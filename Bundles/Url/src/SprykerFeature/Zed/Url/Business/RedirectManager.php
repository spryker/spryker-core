<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Url\Business\Exception\MissingRedirectException;
use SprykerFeature\Zed\Url\Business\Exception\RedirectExistsException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use SprykerFeature\Zed\Url\Dependency\UrlToTouchInterface;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyRedirect;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainerInterface;

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
     * @param UrlQueryContainerInterface $urlQueryContainer
     * @param UrlManagerInterface $urlManager
     * @param UrlToTouchInterface $touchFacade
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlManagerInterface $urlManager,
        UrlToTouchInterface $touchFacade,
    ) {
        $this->urlManager = $urlManager;
        $this->urlQueryContainer = $urlQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @throws RedirectExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return SpyRedirect
     */
    public function createRedirect($toUrl, $status = 301)
    {
        Propel::getConnection()->beginTransaction();

        $redirect = new SpyRedirect();

        $redirect
            ->setToUrl($toUrl)
            ->setStatus($status)

            ->save()
        ;

        Propel::getConnection()->commit();

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
     * @param SpyRedirect $redirectEntity
     *
     * @return RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyRedirect $redirectEntity)
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
        if (is_null($redirect->getIdRedirect())) {
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
        $redirectEntity = new SpyRedirect();

        Propel::getConnection()->beginTransaction();

        $redirectEntity->fromArray($redirectTransfer->toArray());

        $redirectEntity->save();
        Propel::getConnection()->commit();

        $redirectTransfer->setIdRedirect($redirectEntity->getIdRedirect());

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
        $redirectEntity = $this->getRedirectById($redirectTransfer->getIdRedirect());
        $redirectEntity->fromArray($redirectTransfer->toArray());

        if (!$redirectEntity->isModified()) {
            return $redirectTransfer;
        }

        $redirectEntity->save();

        return $redirectTransfer;
    }

    /**
     * @param int $idRedirect
     *
     * @throws MissingRedirectException
     *
     * @return SpyRedirect
     */
    protected function getRedirectById($idRedirect)
    {
        $redirect = $this->urlQueryContainer->queryRedirectById($idRedirect)->findOne();
        if (!$redirect) {
            throw new MissingRedirectException(
                sprintf(
                    'Tried to retrieve a missing redirect with id %s',
                    $idRedirect
                )
            );
        }

        return $redirect;
    }

    /**
     * @param RedirectTransfer $redirect
     */
    public function touchRedirectActive(RedirectTransfer $redirect)
    {
        $this->touchFacade->touchActive(self::ITEM_TYPE_REDIRECT, $redirect->getIdRedirect());
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @throws UrlExistsException
     * @throws MissingLocaleException
     * @throws MissingRedirectException
     *
     * @return UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idRedirect)
    {
        $this->checkRedirectExists($idRedirect);
        $urlEntity = $this->urlManager->createUrl($url, $locale, 'redirect', $idRedirect);

        return $this->urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @return UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idRedirect)
    {
        $urlTransfer  = $this->createRedirectUrl($url, $locale, $idRedirect);
        $this->urlManager->touchUrlActive($urlTransfer->getIdUrl());

        return $urlTransfer;
    }

    /**
     * @param int $idRedirect
     *
     * @throws MissingRedirectException
     */
    protected function checkRedirectExists($idRedirect)
    {
        if (!$this->hasRedirectId($idRedirect)) {
            throw new MissingRedirectException();
        }
    }

    /**
     * @param int $idRedirect
     *
     * @return bool
     */
    protected function hasRedirectId($idRedirect)
    {
        $query = $this->urlQueryContainer->queryRedirectById($idRedirect);

        return $query->count() > 0;
    }

}
