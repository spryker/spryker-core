<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache;

abstract class AbstractStorage implements StorageInterface
{

    /**
     * @var bool
     */
    protected $modified = false;

    /**
     * @return bool
     */
    public function isModified()
    {
        return $this->modified;
    }

    /**
     * @return void
     */
    public function markAsModified()
    {
        $this->modified = true;
    }

}
