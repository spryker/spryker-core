<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ZedRequest\Client\Fixture;

use Spryker\Shared\ZedRequest\Client\AbstractObject;

class CommunicationObject extends AbstractObject
{

    /**
     * @var array
     */
    protected $values = [
        'test1' => null,
        'test2' => null,
        'test3' => [],
        'test4' => [null],
    ];

}
