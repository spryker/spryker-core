<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Generated\Shared\Search\Index1;

use Spryker\Shared\Search\AbstractSearchMap;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF SEARCH MAP GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class ComplexIndexMap extends AbstractSearchMap
{

    const FOO = 'foo';
    const FOO_BAR = 'foo.bar';
    const FOO_BAR_BAZ = 'foo.bar.baz';

    /**
     * @var array
     */
    protected $metadata = [
        self::FOO => [
            'a' => 'asdf',
            'b' => 'qwer',
        ],
        self::FOO_BAR => [
            'a' => 'asdf',
            'b' => 'qwer',
        ],
        self::FOO_BAR_BAZ => [
            'a' => 'asdf',
            'b' => 'qwer',
        ],
    ];

}
