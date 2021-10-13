<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;

abstract class AbstractUrlUpdaterSubject
{
    /**
     * @var array<\Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface>
     */
    protected $beforeSaveObservers = [];

    /**
     * @var array<\Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface>
     */
    protected $afterSaveObservers = [];

    /**
     * @param \Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface $urlWriterObserver
     *
     * @return void
     */
    public function attachBeforeSaveObserver(UrlUpdaterBeforeSaveObserverInterface $urlWriterObserver)
    {
        $this->beforeSaveObservers[] = $urlWriterObserver;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalTransfer
     *
     * @return void
     */
    public function notifyBeforeSaveObservers(UrlTransfer $urlTransfer, UrlTransfer $originalTransfer)
    {
        foreach ($this->beforeSaveObservers as $observer) {
            $observer->handleUrlUpdate($urlTransfer, $originalTransfer);
        }
    }

    /**
     * @param \Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface $urlWriterObserver
     *
     * @return void
     */
    public function attachAfterSaveObserver(UrlUpdaterAfterSaveObserverInterface $urlWriterObserver)
    {
        $this->afterSaveObservers[] = $urlWriterObserver;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalTransfer
     *
     * @return void
     */
    public function notifyAfterSaveObservers(UrlTransfer $urlTransfer, UrlTransfer $originalTransfer)
    {
        foreach ($this->afterSaveObservers as $observer) {
            $observer->handleUrlUpdate($urlTransfer, $originalTransfer);
        }
    }
}
