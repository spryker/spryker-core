<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use InvalidArgumentException;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlReader implements UrlReaderInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer)
    {
        $this->urlQueryContainer = $urlQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrl(UrlTransfer $urlTransfer)
    {
        $urlEntity = $this->queryUrlEntity($urlTransfer)->findOne();

        if (!$urlEntity) {
            return null;
        }

        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrl(UrlTransfer $urlTransfer)
    {
        $ignoreUrlRedirects = ($urlTransfer->getFkResourceRedirect() === null);

        $urlCount = $this->queryUrlEntity($urlTransfer, $ignoreUrlRedirects)->count();

        return $urlCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param bool $ignoreUrlRedirects
     *
     * @throws \InvalidArgumentException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlEntity(UrlTransfer $urlTransfer, $ignoreUrlRedirects = false)
    {
        if ($urlTransfer->getUrl()) {
            $urlQuery = $this->queryUrlEntityByUrl($urlTransfer->getUrl(), $ignoreUrlRedirects);
        } elseif ($urlTransfer->getIdUrl()) {
            $urlQuery = $this->queryUrlEntityById($urlTransfer->getIdUrl(), $ignoreUrlRedirects);
        } else {
            throw new InvalidArgumentException(sprintf(
                'The provided UrlTransfer does not have any data to find URL entity: %s. Set "%s" or "%s" for the UrlTransfer.',
                json_encode($urlTransfer->toArray()),
                UrlTransfer::URL,
                UrlTransfer::ID_URL
            ));
        }

        return $urlQuery;
    }

    /**
     * @param int $idUrl
     * @param bool $ignoreUrlRedirects
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlEntityById($idUrl, $ignoreUrlRedirects)
    {
        return $this->getBaseQuery($ignoreUrlRedirects)->filterByIdUrl($idUrl);
    }

    /**
     * @param string $url
     * @param bool $ignoreUrlRedirects
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlEntityByUrl($url, $ignoreUrlRedirects)
    {
        return $this->getBaseQuery($ignoreUrlRedirects)->filterByUrl($url);
    }

    /**
     * @param bool $ignoreUrlRedirects
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function getBaseQuery($ignoreUrlRedirects)
    {
        if ($ignoreUrlRedirects) {
            return $this->queryUrlByIgnoringRedirects();
        }

        return $this->queryUrl();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlByIgnoringRedirects()
    {
        return $this->urlQueryContainer->queryUrlByIgnoringRedirects();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrl()
    {
        return $this->urlQueryContainer->queryUrls();
    }

}
