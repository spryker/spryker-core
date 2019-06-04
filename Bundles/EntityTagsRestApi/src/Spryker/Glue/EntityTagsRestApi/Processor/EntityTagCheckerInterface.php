<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface EntityTagCheckerInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return bool
     */
    public function isEntityTagRequired(RestResourceInterface $restResource): bool;
}
