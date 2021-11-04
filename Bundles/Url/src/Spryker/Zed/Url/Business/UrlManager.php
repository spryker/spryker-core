<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Spryker\Zed\Url\Dependency\UrlToLocaleInterface;
use Spryker\Zed\Url\Dependency\UrlToTouchInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @deprecated Use business classes from Spryker\Zed\Url\Business\Url namespace.
 */
class UrlManager implements UrlManagerInterface
{
    /**
     * @var string
     */
    public const ITEM_TYPE_URL = 'url';

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Dependency\UrlToLocaleInterface
     */
    protected $localeFacade;

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
     * @param \Spryker\Zed\Url\Dependency\UrlToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Url\Dependency\UrlToTouchInterface $touchFacade
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
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
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return void
     */
    protected function checkUrlDoesNotExist($url)
    {
        if ($this->hasUrl($url)) {
            throw new UrlExistsException(
                sprintf(
                    'Tried to create url %s, but it already exists',
                    $url,
                ),
            );
        }
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
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
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getUrlByPath($url)
    {
        $urlEntity = $this->urlQueryContainer->queryUrl($url)
            ->findOne();

        if (!$urlEntity) {
            throw new MissingUrlException(
                sprintf(
                    'Tried to retrieve url %s, but it is missing',
                    $url,
                ),
            );
        }

        return $urlEntity;
    }

    /**
     * @param int $idUrl
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getUrlById($idUrl)
    {
        $urlEntity = $this->urlQueryContainer->queryUrlById($idUrl)
            ->findOne();

        if (!$urlEntity) {
            throw new MissingUrlException(
                sprintf(
                    'Tried to retrieve url %s, but it is missing',
                    $idUrl,
                ),
            );
        }

        return $urlEntity;
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        $urlEntity = $this->urlQueryContainer
            ->queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
            ->findOne();

        return $this->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return bool
     */
    public function hasResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        return ($this->urlQueryContainer
            ->queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
            ->count() > 0);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode)
    {
        $urlEntityCollection = $this->urlQueryContainer
            ->queryResourceUrlByCategoryNodeId($idCategoryNode)
            ->find();

        $urlTransferCollection = [];
        foreach ($urlEntityCollection as $urlEntity) {
            $urlTransferCollection[] = $this->convertUrlEntityToTransfer($urlEntity);
        }

        return $urlTransferCollection;
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
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl)
    {
        $this->touchFacade->touchDeleted(self::ITEM_TYPE_URL, $idUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    protected function syncUrlEntityWithTransfer(UrlTransfer $urlTransfer, SpyUrl $urlEntity)
    {
        $urlEntity->setFkLocale($urlTransfer->getFkLocale())
            ->setUrl($urlTransfer->getUrl())
            ->setIdUrl($urlTransfer->getIdUrl());

        if ($urlTransfer->getResourceType() && $urlTransfer->getResourceId()) {
            $urlEntity->setResource($urlTransfer->getResourceType(), $urlTransfer->getResourceId());
        }
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
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
     * @deprecated This method will be removed with next major release because of invalid dependency direction.
     *   Use {@link \Spryker\Zed\Product\Business\ProductFacadeInterface::getProductUrl()} instead.
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        $urlEntity = $this->urlQueryContainer
            ->queryUrls()
            ->filterByFkResourceProductAbstract($idProductAbstract)
            ->filterByFkLocale($idLocale)
            ->findOne();

        $urlTransfer = new UrlTransfer();
        if ($urlEntity) {
            $urlTransfer->fromArray($urlEntity->toArray(), true);
        }

        return $urlTransfer;
    }
}
