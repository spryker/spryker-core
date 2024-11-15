<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface CartReorderRestErrorMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapErrorTransferToRestErrorMessageTransfer(
        ErrorTransfer $errorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer,
        string $locale
    ): RestErrorMessageTransfer;
}
