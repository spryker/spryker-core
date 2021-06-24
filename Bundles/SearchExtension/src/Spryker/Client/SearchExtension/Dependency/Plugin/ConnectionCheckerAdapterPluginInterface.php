<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SearchConnectionResponseTransfer;

interface ConnectionCheckerAdapterPluginInterface
{
    /**
     * Specification:
     * - Sends request to Search engine to check connection.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchConnectionResponseTransfer
     */
    public function checkConnection(): SearchConnectionResponseTransfer;
}
