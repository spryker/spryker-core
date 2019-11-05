<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\Dependency\Client;

interface CmsBlockToStorageClientInterface
{
    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys);
}
