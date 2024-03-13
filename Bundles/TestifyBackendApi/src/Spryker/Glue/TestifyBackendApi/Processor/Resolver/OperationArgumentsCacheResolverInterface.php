<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Resolver;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface OperationArgumentsCacheResolverInterface
{
    /**
     * @param string $key
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer> $dynamicFixtureOutput
     *
     * @return void
     */
    public function add(string $key, AbstractTransfer|ArrayObject $dynamicFixtureOutput): void;

    /**
     * @param array<string, mixed> $operationArguments
     *
     * @return array<string, mixed>
     */
    public function resolve(array $operationArguments): array;
}
