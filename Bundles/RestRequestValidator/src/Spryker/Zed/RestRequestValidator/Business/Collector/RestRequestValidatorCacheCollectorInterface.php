<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector;

interface RestRequestValidatorCacheCollectorInterface
{
    /**
     * @param string $storeName
     *
     * @return array
     */
    public function collect(string $storeName): array;
}
