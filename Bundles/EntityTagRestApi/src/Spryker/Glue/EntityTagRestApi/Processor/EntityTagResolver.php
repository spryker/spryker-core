<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi\Processor;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class EntityTagResolver implements EntityTagResolverInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string|null
     */
    public function resolve(RestResourceInterface $restResource): ?string
    {
        /*
uses EntityTagChecker to check that resource required entity tag generation and storing
try to get hash from storage
write to storage if not found
return hash
         * */

    }
}
