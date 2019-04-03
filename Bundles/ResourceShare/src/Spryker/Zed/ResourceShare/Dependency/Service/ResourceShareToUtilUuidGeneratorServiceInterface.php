<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Dependency\Service;

interface ResourceShareToUtilUuidGeneratorServiceInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5FromObjectId(string $name): string;
}
