<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;

abstract class AbstractUrlCreatorSubject
{

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface[]
     */
    protected $beforeSaveObservers = [];

    /**
     * @var \Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface[]
     */
    protected $afterSaveObservers = [];

    /**
     * @param \Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface $urlWriterObserver
     *
     * @return void
     */
    public function attachBeforeSaveObserver(UrlCreatorBeforeSaveObserverInterface $urlWriterObserver)
    {
        $this->beforeSaveObservers[] = $urlWriterObserver;
    }

    /**
     * @param \Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface $urlWriterObserver
     *
     * @return void
     */
    public function attachAfterSaveObserver(UrlCreatorAfterSaveObserverInterface $urlWriterObserver)
    {
        $this->afterSaveObservers[] = $urlWriterObserver;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function notifyBeforeSaveObservers(UrlTransfer $urlTransfer)
    {
        foreach ($this->beforeSaveObservers as $observer) {
            $observer->handleUrlCreation($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function notifyAfterSaveObservers(UrlTransfer $urlTransfer)
    {
        foreach ($this->afterSaveObservers as $observer) {
            $observer->handleUrlCreation($urlTransfer);
        }
    }

}
