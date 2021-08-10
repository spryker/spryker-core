<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Reader;

interface AclEntityReaderInterface
{
    /**
     * @return bool
     */
    public function isActive(): bool;
}
