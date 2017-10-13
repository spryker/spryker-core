<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;

interface NavigationNodeUrlCleanerInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function detachUrlFromNavigationNodes(UrlTransfer $urlTransfer);
}
