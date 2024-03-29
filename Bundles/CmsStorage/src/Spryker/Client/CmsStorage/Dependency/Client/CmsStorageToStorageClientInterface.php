<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Dependency\Client;

interface CmsStorageToStorageClientInterface
{
    /**
     * @param array<string> $keys
     *
     * @return array
     */
    public function getMulti(array $keys);
}
