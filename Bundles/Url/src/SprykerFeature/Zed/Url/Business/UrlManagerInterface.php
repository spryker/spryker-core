<?php

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use SprykerFeature\Zed\Url\Persistence\Exception\MissingResourceException;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrl;

interface UrlManagerInterface
{
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
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource);

    /**
     * @param UrlTransfer $url
     *
     * @return UrlTransfer
     * @throws UrlExistsException
     * @throws MissingUrlException
     * @throws \Exception
     * @throws PropelException
     */
    public function saveUrl(UrlTransfer $url);

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
     * @return SpyUrl
     * @throws MissingUrlException
     */
    public function getUrlByPath($url);

    /**
     * @param int $idUrl
     *
     * @return SpyUrl
     * @throws MissingUrlException
     */
    public function getUrlById($idUrl);

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl);

    /**
     * @param SpyUrl $urlEntity
     *
     * @return UrlTransfer
     * @throws MissingResourceException
     */
    public function convertUrlEntityToTransfer(SpyUrl $urlEntity);

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @return SpyUrl
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource);
}
