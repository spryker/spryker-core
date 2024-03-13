<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Generator;

use Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface DynamicFixtureGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function generate(
        DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
    ): GlueResponseTransfer;
}
