<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\Facade;

interface CmsBlockGuiToProductFacadeInterface
{
    /**
     * @param string $suggestion
     *
     * @return string[]
     */
    public function suggestProductAbstract(string $suggestion): array;
}
