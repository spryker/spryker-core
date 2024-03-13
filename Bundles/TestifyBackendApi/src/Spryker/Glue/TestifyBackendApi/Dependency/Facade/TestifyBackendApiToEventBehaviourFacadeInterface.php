<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Dependency\Facade;

interface TestifyBackendApiToEventBehaviourFacadeInterface
{
    /**
     * @return void
     */
    public function triggerRuntimeEvents(): void;
}
