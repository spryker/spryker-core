<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlUpdater extends AbstractUrlUpdaterSubject implements UrlUpdaterInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlReaderInterface
     */
    protected $urlReader;

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlActivatorInterface
     */
    protected $urlActivator;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Url\UrlReaderInterface $urlReader
     * @param \Spryker\Zed\Url\Business\Url\UrlActivatorInterface $urlActivator
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlReaderInterface $urlReader, UrlActivatorInterface $urlActivator)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlReader = $urlReader;
        $this->urlActivator = $urlActivator;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updateUrl(UrlTransfer $urlTransfer)
    {
        $this->assertUrlTransferForUpdate($urlTransfer);

        $this->urlQueryContainer
            ->getConnection()
            ->beginTransaction();

        $urlTransfer = $this->persistUrlTransfer($urlTransfer);

        $this->urlQueryContainer
            ->getConnection()
            ->commit();

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function assertUrlTransferForUpdate(UrlTransfer $urlTransfer)
    {
        $urlTransfer->requireIdUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function persistUrlTransfer(UrlTransfer $urlTransfer)
    {
        $urlEntity = $this->getUrlById($urlTransfer->getIdUrl());
        $originalUrlEntity = clone $urlEntity;

        $urlEntity->fromArray($urlTransfer->modifiedToArray());

        if ($this->isUrlEntityChanged($urlEntity)) {
            $this->assertUrlDoesNotExist($urlTransfer);
        }

        $urlEntity->save();

        $this->urlActivator->activateUrl($urlTransfer);

        $this->notifyObservers($urlEntity, $originalUrlEntity);

        return $urlTransfer;
    }

    /**
     * @param int $idUrl
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    protected function getUrlById($idUrl)
    {
        $urlEntity = $this->urlQueryContainer->queryUrlById($idUrl)
            ->findOne();

        if (!$urlEntity) {
            throw new MissingUrlException(sprintf(
                'Tried to retrieve url with ID "%s", but it is missing',
                $idUrl
            ));
        }

        return $urlEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return void
     */
    protected function assertUrlDoesNotExist(UrlTransfer $urlTransfer)
    {
        if ($this->urlReader->hasUrl($urlTransfer)) {
            throw new UrlExistsException(sprintf(
                'Tried to create url "%s", but it already exists.',
                $urlTransfer->getUrl()
            ));
        }
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return bool
     */
    protected function isUrlEntityChanged(SpyUrl $urlEntity)
    {
        return $urlEntity->isColumnModified(SpyUrlTableMap::COL_URL);
    }

}
