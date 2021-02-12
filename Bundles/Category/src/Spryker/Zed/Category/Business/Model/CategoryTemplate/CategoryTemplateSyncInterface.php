<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryTemplate;

interface CategoryTemplateSyncInterface
{
    /**
     * @return void
     */
    public function syncFromConfig(): void;
}
