<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Model;

interface KeyFilterInterface
{
    /**
     * @param string $key
     *
     * @return string
     */
    public function escapeKey($key);
}
