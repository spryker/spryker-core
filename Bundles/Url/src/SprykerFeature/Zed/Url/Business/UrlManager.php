<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use SprykerFeature\Zed\Url\Dependency\UrlToLocaleInterface;
use SprykerFeature\Zed\Url\Dependency\UrlToTouchInterface;
use SprykerFeature\Zed\Url\Persistence\Exception\MissingResourceException;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrl;
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
     * @var UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param UrlQueryContainerInterface $urlQueryContainer
     * @param UrlToLocaleInterface $localeFacade
     * @param UrlToTouchInterface $touchFacade
     * @param ConnectionInterface $connection
     */
    public function __construct(
        UrlQueryContainerInterface $urlQueryContainer,
        UrlToLocaleInterface $localeFacade,
        UrlToTouchInterface $touchFacade,
        ConnectionInterface $connection
    ) {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->touchFacade = $touchFacade;
        $this->connection = $connection;
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingLocaleException
     *
     * @return SpyUrl
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource)
    {
        $this->checkUrlDoesNotExist($url);

        $fkLocale = $locale->getIdLocale();
        if ($fkLocale === null) {
            $fkLocale = $this->localeFacade->getLocale($locale->getLocaleName())->getIdLocale();
        }

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl($url)
            ->setFkLocale($fkLocale)
            ->setResource($resourceType, $idResource)
            ->save();

        return $urlEntity;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        $urlCount = $this->urlQueryContainer->queryUrl($url)
            ->count();

        return $urlCount > 0;
    }

    /**
     * @param string $url
     *
     * @throws UrlExistsException
     *
     * @return void
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
     * @throws MissingResourceException
     *
     * @return UrlTransfer
     */
    public function convertUrlEntityToTransfer(SpyUrl $urlEntity)
    {
        $bumps = explode('_', $urlEntity->getResourceType());
        $bumps = array_map('ucfirst', $bumps);

        $setterName = 'setFk' . implode('', $bumps);

        $transferUrl = (new UrlTransfer())
            ->setFkLocale($urlEntity->getFkLocale())
            ->setUrl($urlEntity->getUrl())
            ->setResourceType($urlEntity->getResourceType())
            ->setResourceId($urlEntity->getResourceId())
            ->$setterName($urlEntity->getResourceId())
            ->setIdUrl($urlEntity->getIdUrl());

        return $transferUrl;
    }

    /**
     * @param string $url
     *
     * @throws MissingUrlException
     *
     * @return SpyUrl
     */
    public function getUrlByPath($url)
    {
        $urlEntity = $this->urlQueryContainer->queryUrl($url)
            ->findOne();

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
     * @throws MissingUrlException
     *
     * @return SpyUrl
     */
    public function getUrlById($idUrl)
    {
        $urlEntity = $this->urlQueryContainer->queryUrlById($idUrl)
            ->findOne();

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
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return SpyUrl
     */
    public function getResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        return $this->urlQueryContainer
            ->queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
            ->findOne();
    }

    /**
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl)
    {
        $urlCount = $this->urlQueryContainer->queryUrlById($idUrl)
            ->count();

        return $urlCount > 0;
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl)
    {
        $this->touchFacade->touchActive(self::ITEM_TYPE_URL, $idUrl);
    }

    /**
     * @param $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl)
    {
        $this->touchFacade->touchDeleted(self::ITEM_TYPE_URL, $idUrl);
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer)
    {
        if ($urlTransfer->getIdUrl() === null) {
            return $this->createUrlFromTransfer($urlTransfer);
        } else {
            return $this->updateUrlFromTransfer($urlTransfer);
        }
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @throws MissingUrlException
     * @throws PropelException
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        $urlEntity = $this->urlQueryContainer->queryUrlById($urlTransfer->getIdUrl())
            ->findOne();

        if ($urlEntity) {
            $this->touchUrlDeleted($urlTransfer->getIdUrl());
            $urlEntity->delete();
        }
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer)
    {
        $this->connection->beginTransaction();

        $urlTransfer = $this->saveUrl($urlTransfer);
        $this->touchUrlActive($urlTransfer->getIdUrl());

        $this->connection->commit();

        return $urlTransfer;
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @throws UrlExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return UrlTransfer
     */
    protected function createUrlFromTransfer(UrlTransfer $urlTransfer)
    {
        $this->checkUrlDoesNotExist($urlTransfer->getUrl());

        $urlEntity = new SpyUrl();
        $this->syncUrlEntityWithTransfer($urlTransfer, $urlEntity);

        $urlEntity->save();

        $urlTransfer->setIdUrl($urlEntity->getPrimaryKey());

        return $urlTransfer;
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @throws MissingUrlException
     * @throws UrlExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return UrlTransfer
     */
    protected function updateUrlFromTransfer(UrlTransfer $urlTransfer)
    {
        $urlEntity = $this->getUrlById($urlTransfer->getIdUrl());

        $this->syncUrlEntityWithTransfer($urlTransfer, $urlEntity);

        if (!$urlEntity->isModified()) {
            return $urlTransfer;
        }

        if ($urlEntity->isColumnModified(SpyUrlTableMap::COL_URL)) {
            $this->checkUrlDoesNotExist($urlTransfer->getUrl());
        }

        $urlEntity->save();

        return $urlTransfer;
    }

    /**
     * @param UrlTransfer $urlTransfer
     * @param SpyUrl $urlEntity
     *
     * @return void
     */
    protected function syncUrlEntityWithTransfer(UrlTransfer $urlTransfer, SpyUrl $urlEntity)
    {
        $urlEntity
            ->setFkLocale($urlTransfer->getFkLocale())
            ->setResource($urlTransfer->getResourceType(), $urlTransfer->getResourceId())
            ->setUrl($urlTransfer->getUrl())
            ->setIdUrl($urlTransfer->getIdUrl());
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return SpyUrl
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource)
    {
        $this->checkUrlDoesNotExist($url);

        $fkLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setFkLocale($fkLocale)
            ->setUrl($url)
            ->setResource($resourceType, $idResource);

        $urlEntity->save();

        return $urlEntity;
    }

    /**
     * @param int $idAbstractProduct
     * @param int $idLocale
     *
     * @return UrlTransfer
     */
    public function getUrlByIdAbstractProductAndIdLocale($idAbstractProduct, $idLocale)
    {
        $urlEntity = $this->urlQueryContainer
            ->queryUrls()
            ->filterByFkResourceAbstractProduct($idAbstractProduct)
            ->filterByFkLocale($idLocale)
            ->findOne();

        $urlTransfer = new UrlTransfer();
        if ($urlEntity) {
            $urlTransfer->fromArray($urlEntity->toArray(), true);
        }

        return $urlTransfer;
    }

}
