<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Deletion;

use Generated\Shared\Transfer\UrlTransfer;

abstract class AbstractUrlDeleterSubject
{
    /**
     * @var \Spryker\Zed\Url\Business\Deletion\UrlDeleterBeforeDeleteObserverInterface[]
     */
    protected $beforeDeleteObservers = [];

    /**
     * @var \Spryker\Zed\Url\Business\Deletion\UrlDeleterAfterDeleteObserverInterface[]
     */
    protected $afterDeleteObservers = [];

    /**
     * @param \Spryker\Zed\Url\Business\Deletion\UrlDeleterBeforeDeleteObserverInterface $urlWriterObserver
     *
     * @return void
     */
    public function attachBeforeDeleteObserver(UrlDeleterBeforeDeleteObserverInterface $urlWriterObserver)
    {
        $this->beforeDeleteObservers[] = $urlWriterObserver;
    }

    /**
     * @param \Spryker\Zed\Url\Business\Deletion\UrlDeleterAfterDeleteObserverInterface $urlWriterObserver
     *
     * @return void
     */
    public function attachAfterDeleteObserver(UrlDeleterAfterDeleteObserverInterface $urlWriterObserver)
    {
        $this->afterDeleteObservers[] = $urlWriterObserver;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function notifyBeforeDeleteObservers(UrlTransfer $urlTransfer)
    {
        foreach ($this->beforeDeleteObservers as $observer) {
            $observer->handleUrlDeletion($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function notifyAfterDeleteObservers(UrlTransfer $urlTransfer)
    {
        foreach ($this->afterDeleteObservers as $observer) {
            $observer->handleUrlDeletion($urlTransfer);
        }
    }
}
