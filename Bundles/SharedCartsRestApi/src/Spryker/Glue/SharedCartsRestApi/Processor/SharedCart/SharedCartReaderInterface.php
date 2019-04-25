<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface SharedCartReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer[]
     */
    public function getSharedCartsByCartUuid(RestResourceInterface $resource): array;
}
