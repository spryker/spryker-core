<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestReturnItemsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnsAttributesTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface ReturnResourceMapperInterface
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

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     * @param \Generated\Shared\Transfer\RestReturnsAttributesTransfer $restReturnsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestReturnsAttributesTransfer
     */
    public function mapReturnTransferToRestReturnsAttributesTransfer(
        ReturnTransfer $returnTransfer,
        RestReturnsAttributesTransfer $restReturnsAttributesTransfer
    ): RestReturnsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     * @param \Generated\Shared\Transfer\RestReturnItemsAttributesTransfer $restReturnItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestReturnItemsAttributesTransfer
     */
    public function mapReturnItemTransferToRestReturnItemsAttributesTransfer(
        ReturnItemTransfer $returnItemTransfer,
        RestReturnItemsAttributesTransfer $restReturnItemsAttributesTransfer
    ): RestReturnItemsAttributesTransfer;
}
