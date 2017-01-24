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
        $urlCount = $this->queryUrlEntity($urlTransfer)->count();

        return $urlCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlEntity(UrlTransfer $urlTransfer)
    {
        if ($urlTransfer->getUrl()) {
            $urlQuery = $this->queryUrlEntityByUrl($urlTransfer->getUrl());
        } elseif ($urlTransfer->getIdUrl()) {
            $urlQuery = $this->queryUrlEntityById($urlTransfer->getIdUrl());
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
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlEntityById($idUrl)
    {
        return $this->urlQueryContainer->queryUrlById($idUrl);
    }

    /**
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function queryUrlEntityByUrl($url)
    {
        return $this->urlQueryContainer->queryUrl($url);
    }

}
