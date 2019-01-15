<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

interface ContentStorageClientInterface
{
    /**
     * Specification:
     * - Finds content item in the key-value storage.
     * - Executes data
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function findContentById(int $idContent, string $localeName);
}
