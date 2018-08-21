<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestTaxRateTransfer;
use Generated\Shared\Transfer\RestTaxSetTransfer;
use Generated\Shared\Transfer\TaxRateSetTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\TaxSetsRestApi\TaxSetsRestApiConfig;

class TaxSetsResourceMapper implements TaxSetsResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateSetTransfer $taxRateSetTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapTaxSetsResponseAttributesTransferToRestResponse(TaxRateSetTransfer $taxRateSetTransfer): RestResourceInterface
    {
        $restTaxSetTransfer = (new RestTaxSetTransfer())->fromArray($taxRateSetTransfer->toArray(), true);
        foreach ($taxRateSetTransfer->getTaxRateSetItems() as $taxRateSetItem) {
            $restTaxSetTransfer->addRestTaxRate((new RestTaxRateTransfer())->fromArray($taxRateSetItem->toArray(), true));
        }

        return $this->restResourceBuilder->createRestResource(
            TaxSetsRestApiConfig::RESOURCE_TAX_SETS,
            $taxRateSetTransfer->getUuid(),
            $restTaxSetTransfer
        );
    }
}
