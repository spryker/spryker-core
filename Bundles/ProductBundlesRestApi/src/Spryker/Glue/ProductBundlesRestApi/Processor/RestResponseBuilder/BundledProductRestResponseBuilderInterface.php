<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface BundledProductRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteSkuNotSpecifiedErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createBundledProductEmptyRestResponse(): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $bundledProductRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createBundledProductCollectionRestResponse(
        array $bundledProductRestResources
    ): RestResponseInterface;
}
