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
     * @var \Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver[]
     */
    protected $observers = [];

    /**
     * @param \Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver $urlWriterObserver
     *
     * @return void
     */
    public function attachObserver(AbstractUrlCreatorObserver $urlWriterObserver)
    {
        $this->observers[] = $urlWriterObserver;
    }

    /**
     * @param \Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver $urlWriterObserver
     *
     * @return void
     */
    public function detachObserver(AbstractUrlCreatorObserver $urlWriterObserver)
    {
        $key = array_search($urlWriterObserver, $this->observers, true);

        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function notifyObservers(UrlTransfer $urlTransfer)
    {
        foreach ($this->observers as $observer) {
            $observer->update($urlTransfer);
        }
    }

}
