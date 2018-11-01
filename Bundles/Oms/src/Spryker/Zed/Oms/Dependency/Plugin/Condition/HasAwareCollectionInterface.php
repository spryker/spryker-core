<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin\Condition;

interface HasAwareCollectionInterface
{
    /**
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);
}
