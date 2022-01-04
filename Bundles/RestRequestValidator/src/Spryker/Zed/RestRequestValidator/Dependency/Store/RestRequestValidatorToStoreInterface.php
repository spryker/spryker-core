<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Dependency\Store;

/**
 * @deprecated Use {@link \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface} instead.
 */
interface RestRequestValidatorToStoreInterface
{
    /**
     * @return array<string>
     */
    public function getAllowedStores();
}
