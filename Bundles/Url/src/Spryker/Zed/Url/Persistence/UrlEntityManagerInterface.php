<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

interface UrlEntityManagerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     * @param bool|null $isNew
     *
     * @return void
     */
    public function saveUrlEntities(array $urlTransfers, ?bool $isNew = true): void;
}
