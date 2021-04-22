<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

/**
 * Could be used together with *BeforeActionTerminatePluginInterface to give the ability not terminate controller processing in case of failure.
 */
interface TerminationAwareBeforeActionPluginInterface
{
    /**
     * Specification:
     * - Describes should the controller before action plugin process be stopped in case of failure.
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure(): bool;
}
