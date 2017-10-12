<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Dependency\Plugin;

use Generated\Shared\Transfer\UrlTransfer;

interface UrlCreatePluginInterface
{
    /**
     * Specification:
     * - This plugin is executed before and/or after new URL entity creation, depending for which event was it provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function create(UrlTransfer $urlTransfer);
}
