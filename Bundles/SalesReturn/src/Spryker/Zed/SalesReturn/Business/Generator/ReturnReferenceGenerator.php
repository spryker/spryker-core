<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Generator;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class ReturnReferenceGenerator implements ReturnReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface
     */
    protected $salesReturnRepository;

    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface $salesReturnRepository
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     */
    public function __construct(
        SalesReturnRepositoryInterface $salesReturnRepository,
        SalesReturnConfig $salesReturnConfig
    ) {
        $this->salesReturnRepository = $salesReturnRepository;
        $this->salesReturnConfig = $salesReturnConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string
     */
    public function generateReturnReference(ReturnTransfer $returnTransfer): string
    {
        if ($returnTransfer->getCustomerReference()) {
            return $this->getCustomerReturnReference($returnTransfer);
        }

        return $this->getGuestReturnReference($returnTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string
     */
    protected function getCustomerReturnReference(ReturnTransfer $returnTransfer): string
    {
        return sprintf(
            $this->salesReturnConfig->getReturnReferenceFormat(),
            $returnTransfer->getCustomerReference(),
            $this->salesReturnRepository->countCustomerReturns($returnTransfer->getCustomerReference()) + 1
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string
     */
    protected function getGuestReturnReference(ReturnTransfer $returnTransfer): string
    {
        return sprintf(
            $this->salesReturnConfig->getGuestReturnReferenceFormat(),
            $returnTransfer->getStore(),
            $this->salesReturnRepository->countCustomerReturns() + 1
        );
    }
}
