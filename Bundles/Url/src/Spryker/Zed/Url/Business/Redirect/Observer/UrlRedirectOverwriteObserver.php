<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\Base\SpyUrlRedirect;
use Spryker\Zed\Url\Business\Deletion\UrlDeleterInterface;
use Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface;
use Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectOverwriteObserver implements UrlCreatorBeforeSaveObserverInterface, UrlUpdaterBeforeSaveObserverInterface
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Business\Deletion\UrlDeleterInterface
     */
    protected $urlDeleter;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     * @param \Spryker\Zed\Url\Business\Deletion\UrlDeleterInterface $urlDeleter
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer, UrlDeleterInterface $urlDeleter)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        $this->urlDeleter = $urlDeleter;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function handleUrlCreation(UrlTransfer $urlTransfer)
    {
        $this->handleUrlRedirectOverwrite($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return void
     */
    public function handleUrlUpdate(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer)
    {
        if ($urlTransfer->getUrl() === $originalUrlTransfer->getUrl()) {
            return;
        }

        $this->handleUrlRedirectOverwrite($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function handleUrlRedirectOverwrite(UrlTransfer $urlTransfer)
    {
        $urlRedirectEntity = $this->findUrlRedirectEntity($urlTransfer);

        if (!$urlRedirectEntity) {
            return;
        }

        $this->deleteUrlRedirectEntity($urlRedirectEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function findUrlRedirectEntity(UrlTransfer $urlTransfer)
    {
        return $this->urlQueryContainer
            ->queryUrlRedirectBySourceUrl($urlTransfer->getUrl())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Url\Persistence\Base\SpyUrlRedirect $urlRedirectEntity
     *
     * @return void
     */
    protected function deleteUrlRedirectEntity(SpyUrlRedirect $urlRedirectEntity)
    {
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $this->urlDeleter->deleteUrlRedirect($urlRedirectTransfer);
    }

}
