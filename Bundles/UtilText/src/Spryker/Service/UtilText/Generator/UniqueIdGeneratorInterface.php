<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Generator;

interface UniqueIdGeneratorInterface
{
    /**
     * @param string $prefix
     * @param bool $moreEntropy
     *
     * @return string
     */
    public function generateUniqueId(string $prefix = '', bool $moreEntropy = false): string;
}
