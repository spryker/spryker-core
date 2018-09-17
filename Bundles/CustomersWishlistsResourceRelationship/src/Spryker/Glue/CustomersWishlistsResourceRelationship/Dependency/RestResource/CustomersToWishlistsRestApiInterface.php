<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CustomersToWishlistsRestApiInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function findCustomerWishlists(RestRequestInterface $restRequest): array;
}
