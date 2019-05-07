<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Error;

use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface RestCheckoutErrorMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapRestCheckoutErrorTransferToRestErrorTransfer(
        RestCheckoutErrorTransfer $restCheckoutErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapLocalizedRestCheckoutErrorTransferToRestErrorTransfer(
        RestCheckoutErrorTransfer $restCheckoutErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer,
        string $localeCode
    ): RestErrorMessageTransfer;
}
