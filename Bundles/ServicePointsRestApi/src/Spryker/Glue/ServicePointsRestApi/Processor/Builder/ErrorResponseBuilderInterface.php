<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Builder;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ErrorResponseBuilderInterface
{
    /**
     * @param string $errorMessage
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponse(string $errorMessage, string $localeName): RestResponseInterface;
}
