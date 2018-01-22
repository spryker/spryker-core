<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Dependency\Facade;

interface PropelToLogInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data);
}
