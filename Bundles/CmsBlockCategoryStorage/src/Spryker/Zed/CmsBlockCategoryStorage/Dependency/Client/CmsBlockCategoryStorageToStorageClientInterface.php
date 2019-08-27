<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Dependency\Client;

interface CmsBlockCategoryStorageToStorageClientInterface
{
    /**
     * @param string $key
     *
     * @return array
     */
    public function get($key);
}
