<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\KeyBuilder;

interface KeyBuilderInterface
{
    /**
     * @param mixed $data
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return string
     */
    public function generateKey($data, $localeName, ?string $storeName = null): string;
}
