<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\BulkSaver;

interface UrlBulkSaverInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function save(array $urlTransfers): array;
}
