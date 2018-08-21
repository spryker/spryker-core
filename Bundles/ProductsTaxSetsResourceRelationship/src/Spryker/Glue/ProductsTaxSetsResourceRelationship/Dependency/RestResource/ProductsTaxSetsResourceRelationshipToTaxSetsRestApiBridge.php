<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductsTaxSetsResourceRelationshipToTaxSetsRestApiBridge implements ProductsTaxSetsResourceRelationshipToTaxSetsRestApiInterface
{
    /**
     * @var \Spryker\Glue\TaxSetsRestApi\TaxSetsRestApiResource
     */
    protected $taxSetsRestApiResource;

    /**
     * @param \Spryker\Glue\TaxSetsRestApi\TaxSetsRestApiResource $taxSetsRestApiResource
     */
    public function __construct($taxSetsRestApiResource)
    {
        $this->taxSetsRestApiResource = $taxSetsRestApiResource;
    }

    /**
     * @param string $abstractProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductTaxSetsByAbstractProductSku(string $abstractProductSku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->taxSetsRestApiResource
            ->findAbstractProductTaxSetsByAbstractProductSku($abstractProductSku, $restRequest);
    }
}
