<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Dependency\External;

interface DynamicEntityGuiToInflectorInterface
{
    /**
     * @param string $word
     *
     * @return string
     */
    public function pluralize(string $word): string;
}
