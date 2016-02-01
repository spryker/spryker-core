<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Spryker\Zed\Url\Persistence\Exception\MissingResourceException;
use Orm\Zed\Url\Persistence\SpyUrl;

interface UrlManagerInterface
{

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource);

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer);

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer);

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer);

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url);

    /**
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl);

    /**
     * @param string $url
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getUrlByPath($url);

    /**
     * @param int $idUrl
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getUrlById($idUrl);

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl);

    /**
     * @param $idUrl
     */
    public function touchUrlDeleted($idUrl);

    /**
     * @param SpyUrl $urlEntity
     *
     * @throws \Spryker\Zed\Url\Persistence\Exception\MissingResourceException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function convertUrlEntityToTransfer(SpyUrl $urlEntity);

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource);

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

}
