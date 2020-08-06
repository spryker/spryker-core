<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface CartCodeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapMessageTransferToRestErrorMessageTransfer(
        MessageTransfer $messageTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer;
}
