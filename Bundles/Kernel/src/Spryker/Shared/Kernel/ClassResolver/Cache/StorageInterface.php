<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache;

interface StorageInterface
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function persist(array $data);

    /**
     * @return array
     */
    public function getData();
}
