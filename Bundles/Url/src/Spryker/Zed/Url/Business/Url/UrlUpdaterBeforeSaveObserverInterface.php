<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;

interface UrlUpdaterBeforeSaveObserverInterface
{

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return void
     */
    public function handleUrlUpdate(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer);

}
