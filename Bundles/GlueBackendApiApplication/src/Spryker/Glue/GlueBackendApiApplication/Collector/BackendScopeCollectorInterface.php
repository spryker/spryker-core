<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Collector;

interface BackendScopeCollectorInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\OauthScopeFindTransfer>
     */
    public function collect(): array;
}
