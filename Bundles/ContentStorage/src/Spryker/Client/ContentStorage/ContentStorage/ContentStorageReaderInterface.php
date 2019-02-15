<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\ContentStorage;

interface ContentStorageReaderInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|null
     */
    public function findContentById(int $idContent, string $localeName): ?array;
}
