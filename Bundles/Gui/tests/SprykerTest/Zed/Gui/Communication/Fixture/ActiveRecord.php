<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Communication\Fixture;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class ActiveRecord implements ActiveRecordInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return false;
    }
}
