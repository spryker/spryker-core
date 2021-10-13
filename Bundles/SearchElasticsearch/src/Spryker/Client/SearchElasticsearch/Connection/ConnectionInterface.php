<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Connection;

use Generated\Shared\Transfer\SearchConnectionResponseTransfer;

interface ConnectionInterface
{
    /**
     * @return \Generated\Shared\Transfer\SearchConnectionResponseTransfer
     */
    public function checkConnection(): SearchConnectionResponseTransfer;
}
