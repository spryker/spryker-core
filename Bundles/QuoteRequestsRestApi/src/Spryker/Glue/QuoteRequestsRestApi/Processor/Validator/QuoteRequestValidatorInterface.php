<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Validator;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function validateDeliveryDate(QuoteRequestTransfer $quoteRequestTransfer): bool;
}
