<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\GlueResponseTransfer;

interface DynamicFixtureResponseBuilderInterface
{
    /**
     * @param array<string, \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>|null> $dynamicFixtures
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createDynamicFixtureResponse(array $dynamicFixtures): GlueResponseTransfer;
}
