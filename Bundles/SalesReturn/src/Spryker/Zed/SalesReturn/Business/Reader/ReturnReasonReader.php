<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface;

class ReturnReasonReader implements ReturnReasonReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface
     */
    protected $salesReturnRepository;

    /**
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface $salesReturnRepository
     */
    public function __construct(SalesReturnRepositoryInterface $salesReturnRepository)
    {
        $this->salesReturnRepository = $salesReturnRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        $returnReasonTransfers = $this->salesReturnRepository->getReturnReasons($returnReasonFilterTransfer);

        return (new ReturnReasonCollectionTransfer())
            ->setReturnReasons(new ArrayObject($returnReasonTransfers));
    }
}
