<?php

namespace SprykerFeature\Zed\Url\Business;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlUrlTransfer;
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
     * @return Redirect
     */
    public function convertRedirectEntityToTransfer(SpyRedirect $redirectEntity)
    {
        $transferRedirect = new \Generated\Shared\Transfer\UrlRedirectTransfer();
        $transferRedirect->fromArray($redirectEntity->toArray());

        return $transferRedirect;
    }

    /**
     * @param Redirect $redirect
     *
     * @return Redirect
     * @throws RedirectExistsException
     */
    public function saveRedirect(Redirect $redirect)
    {
        if (is_null($redirect->getIdRedirect())) {
            return $this->createRedirectFromTransfer($redirect);
        } else {
            return $this->updateRedirectFromTransfer($redirect);
        }
    }

    /**
     * @param Redirect $redirectTransfer
     *
     * @return Redirect
     * @throws RedirectExistsException
     * @throws \Exception
     * @throws PropelException
     */
    protected function createRedirectFromTransfer(Redirect $redirectTransfer)
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
     * @param Redirect $redirectTransfer
     *
     * @return Redirect
     * @throws MissingRedirectException
     * @throws \Exception
     * @throws PropelException
     */
    protected function updateRedirectFromTransfer(Redirect $redirectTransfer)
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
     * @param Redirect $redirect
     */
    public function touchRedirectActive(Redirect $redirect)
    {
        $this->touchFacade->touchActive(self::ITEM_TYPE_REDIRECT, $redirect->getIdRedirect());
    }

    /**
     * @param string $url
     * @param LocaleDto $locale
     * @param int $idRedirect
     *
     * @return Url
     * @throws UrlExistsException
     * @throws MissingLocaleException
     * @throws MissingRedirectException
     */
    public function createRedirectUrl($url, LocaleDto $locale, $idRedirect)
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
