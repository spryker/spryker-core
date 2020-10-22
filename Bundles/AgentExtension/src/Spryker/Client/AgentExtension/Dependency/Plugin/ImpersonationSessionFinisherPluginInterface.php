<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentExtension\Dependency\Plugin;

/**
 * Plugin runs when customer impersonation is finished.
 * Implement it when you need to execute a cleanup/perform action before customer impersonation is finished.
 */
interface ImpersonationSessionFinisherPluginInterface
{
    /**
     * Specification:
     * - Finishes process related for logged customer at the end of customer impersonation.
     *
     * @api
     *
     * @return void
     */
    public function finish(): void;
}
