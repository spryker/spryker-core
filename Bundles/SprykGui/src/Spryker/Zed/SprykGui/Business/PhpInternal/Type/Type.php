<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\PhpInternal\Type;

class Type implements TypeInterface
{
    /**
     * @return array
     */
    public function getTypes(): array
    {
        return [
            'void',
            'string',
            'bool',
            'array',
            'int',
            'float',
            'mixed',
            'object',
            'callback',
            'iterable',
        ];
    }
}
