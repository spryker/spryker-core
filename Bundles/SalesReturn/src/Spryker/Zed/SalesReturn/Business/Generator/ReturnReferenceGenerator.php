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
        $this->assertRequirements($returnTransfer);

        $customerReference = $returnTransfer->getCustomer()->getCustomerReference();
        $customerReturnCounter = $this->salesReturnRepository->countCustomerReturns($customerReference);

        return sprintf(
            $this->salesReturnConfig->getReturnReferenceFormat(),
            $customerReference,
            $customerReturnCounter + 1
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return void
     */
    protected function assertRequirements(ReturnTransfer $returnTransfer): void
    {
        $returnTransfer
            ->requireCustomer()
            ->getCustomer()
                ->requireCustomerReference();
    }
}
