<?php

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
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
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param UrlQueryContainerInterface $urlQueryContainer
     * @param UrlManagerInterface $urlManager
     * @param UrlToTouchInterface $touchFacade
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlManagerInterface $urlManager,
        UrlToTouchInterface $touchFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->urlManager = $urlManager;
        $this->urlQueryContainer = $urlQueryContainer;
        $this->locator = $locator;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return SpyRedirect
     * @throws RedirectExistsException
     * @throws \Exception
     * @throws PropelException
     */
    public function createRedirect($toUrl, $status = 301)
    {
        Propel::getConnection()->beginTransaction();

        $redirect = $this->locator->url()->entitySpyRedirect();

        $redirect
            ->setToUrl($toUrl)
            ->setStatus($status)

            ->save()
        ;

        Propel::getConnection()->commit();

        return $redirect;
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
     * @return RedirectTransfer
     * @throws RedirectExistsException
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
     * @param RedirectTransfer $redirectTransfer
     *
     * @return RedirectTransfer
     * @throws RedirectExistsException
     * @throws \Exception
     * @throws PropelException
     */
    protected function createRedirectFromTransfer(RedirectTransfer $redirectTransfer)
    {
        $redirectEntity = $this->locator->url()->entitySpyRedirect();

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
     * @return RedirectTransfer
     * @throws MissingRedirectException
     * @throws \Exception
     * @throws PropelException
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
     * @return SpyRedirect
     * @throws MissingRedirectException
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
     * @return UrlTransfer
     * @throws UrlExistsException
     * @throws MissingLocaleException
     * @throws MissingRedirectException
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idRedirect)
    {
        $this->checkRedirectExists($idRedirect);
        $urlEntity = $this->urlManager->createUrl($url, $locale, 'redirect', $idRedirect);

        return $this->urlManager->convertUrlEntityToTransfer($urlEntity);
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
