<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor\Fixtures;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class Entity implements ActiveRecordInterface
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
        ];
    }
}
