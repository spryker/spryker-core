<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface EntityTagResolverInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string|null
     */
    public function resolve(RestResourceInterface $restResource): ?string;
}
