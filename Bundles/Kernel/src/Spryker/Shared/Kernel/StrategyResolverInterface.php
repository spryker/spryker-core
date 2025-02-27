<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

/**
 * @template S
 */
interface StrategyResolverInterface
{
    /**
     * Specification:
     * - If the context selector is not found, the fallback context is used.
     * - If the fallback context is not set either, an exception is thrown.
     *
     * @param string|null $contextSelector
     *
     * @throws \InvalidArgumentException
     *
     * @return S
     */
    public function get(?string $contextSelector);
}
