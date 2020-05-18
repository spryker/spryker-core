<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Propel\Runtime\Propel;

trait InstancePoolingHelperTrait
{
    /**
     * Returns true if method changed the instance pooling state.
     *
     * @return bool
     */
    public function enableInstancePooling(): bool
    {
        return Propel::enableInstancePooling();
    }

    /**
     * Returns true if method changed the instance pooling state.
     *
     * @return bool
     */
    public function disableInstancePooling(): bool
    {
        return Propel::disableInstancePooling();
    }

    /**
     * Returns boolean whether the pooling is enabled or not.
     *
     * @return bool
     */
    public function isInstancePoolingEnabled(): bool
    {
        return Propel::isInstancePoolingEnabled();
    }
}
