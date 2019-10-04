<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Dependency\Facade;

use Generated\Shared\Transfer\UrlTransfer;

interface MerchantProfileGuiToUrlFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlCaseInsensitive($urlTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer;
}
