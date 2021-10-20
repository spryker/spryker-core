<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use InvalidArgumentException;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;
use Spryker\Zed\Url\Persistence\UrlRepositoryInterface;

class UrlReader implements UrlReaderInterface
{
    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlRepositoryInterface
     */
    protected $urlRepository;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Persistence\UrlRepositoryInterface $urlRepository
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlRepositoryInterface $urlRepository)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlRepository = $urlRepository;
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
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer
    {
        $this->assertUrlSearchableParameters($urlTransfer);

        return $this->urlRepository->findUrlCaseInsensitive($urlTransfer);
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
     *
     * @return bool
     */
    public function hasUrlCaseInsensitive(UrlTransfer $urlTransfer): bool
    {
        $this->assertUrlSearchableParameters($urlTransfer);

        $ignoreUrlRedirects = ($urlTransfer->getFkResourceRedirect() === null);

        return $this->urlRepository->hasUrlCaseInsensitive(
            $urlTransfer,
            $ignoreUrlRedirects,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlOrRedirectedUrl(UrlTransfer $urlTransfer)
    {
        $urlCount = $this->queryUrlEntity($urlTransfer, false)->count();

        return $urlCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlOrRedirectedUrlCaseInsensitive(UrlTransfer $urlTransfer): bool
    {
        $this->assertUrlSearchableParameters($urlTransfer);

        return $this->urlRepository->hasUrlCaseInsensitive(
            $urlTransfer,
            false,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\ProductStorage\Exception\InvalidArgumentException
     *
     * @return void
     */
    protected function assertUrlSearchableParameters(UrlTransfer $urlTransfer): void
    {
        if ($urlTransfer->getIdUrl() !== null || $urlTransfer->getUrl() !== null) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'The provided UrlTransfer does not have any data to find URL entity: %s. Set "%s" or "%s" for the UrlTransfer.',
            json_encode($urlTransfer->toArray()),
            UrlTransfer::URL,
            UrlTransfer::ID_URL,
        ));
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
                UrlTransfer::ID_URL,
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
