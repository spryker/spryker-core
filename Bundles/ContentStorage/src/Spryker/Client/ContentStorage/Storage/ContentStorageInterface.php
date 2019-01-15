<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\Storage;

interface ContentStorageInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function findContentById(int $idContent, string $localeName);
}
