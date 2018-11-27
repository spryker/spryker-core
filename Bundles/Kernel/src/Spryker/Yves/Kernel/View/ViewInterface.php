<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\View;

use ArrayAccess;

interface ViewInterface extends ArrayAccess
{
    /**
     * @return string|null
     */
    public function getTemplate();

    /**
     * @return array
     */
    public function getData(): array;
}
