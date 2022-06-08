<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Hasher;

interface HasherInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function encrypt(string $string): string;

    /**
     * @param string $string
     * @param string $hash
     *
     * @return bool
     */
    public function validate(string $string, string $hash): bool;
}
