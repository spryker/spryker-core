<?php

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use SprykerFeature\Zed\Url\Dependency\UrlToLocaleInterface;
use SprykerFeature\Zed\Url\Dependency\UrlToTouchInterface;
use SprykerFeature\Zed\Url\Persistence\Exception\MissingResourceException;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrl;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlManager implements UrlManagerInterface
{
    const ITEM_TYPE_URL = 'url';
    /**
     * @var UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var UrlToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var UrlToTouchInterface
     */
    private $touchFacade;

    /**
     * @param UrlQueryContainerInterface $urlQueryContainer
     * @param UrlToLocaleInterface $localeFacade
     * @param UrlToTouchInterface $touchFacade
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlToLocaleInterface $localeFacade,
        UrlToTouchInterface $touchFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->locator = $locator;
        $this->localeFacade = $localeFacade;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @return SpyUrl
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingLocaleException
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource)
    {
        $this->checkUrlDoesNotExist($url);

        $fkLocale = $locale->getIdLocale();
        if (null === $fkLocale) {
            $fkLocale = $this->localeFacade->getLocale($locale->getLocaleName())->getIdLocale();
        }

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl($url)
            ->setFkLocale($fkLocale)
            ->setResource($resourceType, $idResource)

            ->save()
        ;

        return $urlEntity;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        $urlCount = $this->urlQueryContainer->queryUrl($url)->count();

        return $urlCount > 0;
    }

    /**
     * @param string $url
     *
     * @throws UrlExistsException
     */
    protected function checkUrlDoesNotExist($url)
    {
        if ($this->hasUrl($url)) {
            throw new UrlExistsException(
                sprintf(
                    'Tried to create url %s, but it already exists',
                    $url
                )
            );
        }
    }

    /**
     * @param SpyUrl $urlEntity
     *
     * @return UrlTransfer
     * @throws MissingResourceException
     */
    public function convertUrlEntityToTransfer(SpyUrl $urlEntity)
    {
        $bumps = explode('_', $urlEntity->getResourceType());
        $bumps = array_map('ucfirst', $bumps);

        $setterName = 'setFk' . implode('', $bumps);

        $transferUrl = new UrlTransfer();
        $transferUrl
            ->setFkLocale($urlEntity->getFkLocale())
            ->setUrl($urlEntity->getUrl())
            ->setResourceType($urlEntity->getResourceType())
            ->setResourceId($urlEntity->getResourceId())
            ->$setterName($urlEntity->getResourceId())
            ->setIdUrl($urlEntity->getIdUrl())
        ;

        return $transferUrl;
    }

    /**
     * @param string $url
     *
     * @return SpyUrl
     * @throws MissingUrlException
     */
    public function getUrlByPath($url)
    {
        $urlEntity = $this->urlQueryContainer->queryUrl($url)->findOne();

        if (!$urlEntity) {
            throw new MissingUrlException(
                sprintf(
                    'Tried to retrieve url %s, but it is missing',
                    $url
                )
            );
        }

        return $urlEntity;
    }

    /**
     * @param int $idUrl
     *
     * @return SpyUrl
     * @throws MissingUrlException
     */
    public function getUrlById($idUrl)
    {
        $urlEntity = $this->urlQueryContainer->queryUrlById($idUrl)->findOne();

        if (!$urlEntity) {
            throw new MissingUrlException(
                sprintf(
                    'Tried to retrieve url %s, but it is missing',
                    $idUrl
                )
            );
        }

        return $urlEntity;
    }

    /**
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl)
    {
        $urlCount = $this->urlQueryContainer->queryUrlById($idUrl)->count();

        return $urlCount > 0;
    }

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl)
    {
        $this->touchFacade->touchActive(self::ITEM_TYPE_URL, $idUrl);
    }

    /**
     * @param UrlTransfer $url
     *
     * @return UrlTransfer
     * @throws UrlExistsException
     * @throws MissingUrlException
     * @throws \Exception
     * @throws PropelException
     */
    public function saveUrl(UrlTransfer $url)
    {
        if (is_null($url->getIdUrl())) {
            return $this->createUrlFromTransfer($url);
        } else {
            return $this->updateUrlFromTransfer($url);
        }
    }

    /**
     * @param UrlTransfer $url
     *
     * @return UrlTransfer
     * @throws UrlExistsException
     * @throws \Exception
     * @throws PropelException
     */
    protected function createUrlFromTransfer(UrlTransfer $url)
    {
        $this->checkUrlDoesNotExist($url->getUrl());

        $urlEntity = $this->locator->url()->entitySpyUrl();
        $this->syncUrlEntityWithTransfer($url, $urlEntity);

        $urlEntity->save();

        $url->setIdUrl($urlEntity->getPrimaryKey());

        return $url;
    }

    /**
     * @param UrlTransfer $url
     *
     * @return UrlTransfer
     * @throws MissingUrlException
     * @throws UrlExistsException
     * @throws \Exception
     * @throws PropelException
     */
    protected function updateUrlFromTransfer(UrlTransfer $url)
    {
        $urlEntity = $this->getUrlById($url->getIdUrl());

        $this->syncUrlEntityWithTransfer($url, $urlEntity);

        if (!$urlEntity->isModified()) {
            return $url;
        }

        if ($urlEntity->isColumnModified(SpyUrlTableMap::COL_URL)) {
            $this->checkUrlDoesNotExist($url->getUrl());
        }

        $urlEntity->save();
        return $url;
    }

    /**
     * @param UrlTransfer $urlTransfer
     * @param SpyUrl $urlEntity
     */
    protected function syncUrlEntityWithTransfer(UrlTransfer $urlTransfer, SpyUrl $urlEntity)
    {

        $urlEntity
            ->setFkLocale($urlTransfer->getFkLocale())
            ->setResource($urlTransfer->getResourceType(), $urlTransfer->getResourceId())
            ->setUrl($urlTransfer->getUrl())
            ->setIdUrl($urlTransfer->getIdUrl())
        ;
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @return SpyUrl
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource)
    {
        $this->checkUrlDoesNotExist($url);

        $fkLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setFkLocale($fkLocale)
            ->setUrl($url)
            ->setResource($resourceType, $idResource)
        ;

        $urlEntity->save();

        return $urlEntity;
    }
}
