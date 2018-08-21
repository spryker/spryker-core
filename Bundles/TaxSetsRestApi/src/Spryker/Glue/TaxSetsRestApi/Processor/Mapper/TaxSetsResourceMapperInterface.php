<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\TaxRateSetTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface TaxSetsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxRateSetTransfer $taxRateSetTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapTaxSetsResponseAttributesTransferToRestResponse(TaxRateSetTransfer $taxRateSetTransfer): RestResourceInterface;
}
