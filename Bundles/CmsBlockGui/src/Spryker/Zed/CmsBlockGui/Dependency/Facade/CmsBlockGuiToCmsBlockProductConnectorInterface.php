<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\Facade;

interface CmsBlockGuiToCmsBlockProductConnectorInterface
{
    /**
     * @param string $suggestion
     *
     * @return array
     */
    public function suggestProductAbstract(string $suggestion): array;
}
