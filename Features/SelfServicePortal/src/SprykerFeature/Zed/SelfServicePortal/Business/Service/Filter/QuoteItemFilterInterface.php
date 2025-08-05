<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Filter;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteItemFilterInterface
{
    public function filterOutServicesWithoutShipmentTypes(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
