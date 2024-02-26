<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Resolver;

interface StoreResolverInterface
{
    /**
     * @throws \Exception
     *
     * @return string
     */
    public function resolveStoreName(): string;
}
