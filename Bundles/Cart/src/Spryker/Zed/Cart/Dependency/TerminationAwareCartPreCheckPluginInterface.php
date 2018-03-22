<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency;

interface TerminationAwareCartPreCheckPluginInterface
{
    /**
     * Specification:
     * - Describes should the cart pre-check process be stopped in case of failure
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure();
}
