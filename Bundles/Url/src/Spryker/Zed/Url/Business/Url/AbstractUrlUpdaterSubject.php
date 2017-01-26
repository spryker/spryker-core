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
     * @var \Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterObserver[]
     */
    protected $observers = [];

    /**
     * @param \Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterObserver $urlWriterObserver
     *
     * @return void
     */
    public function attachObserver(AbstractUrlUpdaterObserver $urlWriterObserver)
    {
        $this->observers[] = $urlWriterObserver;
    }

    /**
     * @param \Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterObserver $urlWriterObserver
     *
     * @return void
     */
    public function detachObserver(AbstractUrlUpdaterObserver $urlWriterObserver)
    {
        $key = array_search($urlWriterObserver, $this->observers, true);

        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalTransfer
     *
     * @return void
     */
    public function notifyObservers(UrlTransfer $urlTransfer, UrlTransfer $originalTransfer)
    {
        foreach ($this->observers as $observer) {
            $observer->update($urlTransfer, $originalTransfer);
        }
    }

}
