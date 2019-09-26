<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\KeyBuilder;

interface UrlRedirectStorageKeyBuilderInterface
{
    /**
     * @param int $idRedirectUrl
     *
     * @return string
     */
    public function generateKey(int $idRedirectUrl): string;
}
