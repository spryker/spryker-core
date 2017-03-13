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
     * TODO: add specification
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return mixed
     */
    public function create(UrlTransfer $urlTransfer);

}
