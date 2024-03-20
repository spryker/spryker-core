<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\SalesPaymentDetail\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PaymentCreatedBuilder;
use Generated\Shared\DataBuilder\SalesPaymentDetailBuilder;
use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Generated\Shared\Transfer\SalesPaymentDetailTransfer;
use Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail;
use Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetailQuery;

class SalesPaymentDetailHelper extends Module
{
    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\PaymentCreatedTransfer
     */
    public function havePaymentCreatedTransfer(array $seed = []): PaymentCreatedTransfer
    {
        return (new PaymentCreatedBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer
     */
    public function haveSalesPaymentDetail(array $seed = []): SalesPaymentDetailTransfer
    {
        $salesPaymentDetailTransfer = (new SalesPaymentDetailBuilder($seed))->build();
        $salesPaymentDetailEntity = (new SpySalesPaymentDetail())->fromArray($salesPaymentDetailTransfer->toArray());
        $salesPaymentDetailEntity->save();

        $salesPaymentDetailTransfer->fromArray($salesPaymentDetailEntity->toArray(), true);

        return $salesPaymentDetailTransfer;
    }

    /**
     * @param string $paymentReference
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     *
     * @return void
     */
    public function assertSalesPaymentDetailByPaymentReferenceIsIdentical(
        string $paymentReference,
        SalesPaymentDetailTransfer $salesPaymentDetailTransfer
    ): void {
        $salesPaymentDetailQuery = new SpySalesPaymentDetailQuery();

        $salesPaymentDetailCollection = $salesPaymentDetailQuery->findByPaymentReference($paymentReference);

        $this->assertCount(1, $salesPaymentDetailCollection);

        /** @var \Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail $salesPaymentDetailEntity */
        $salesPaymentDetailEntity = $salesPaymentDetailCollection->getFirst();
        $this->assertNotNull($salesPaymentDetailEntity);

        $this->assertSame($salesPaymentDetailTransfer->getPaymentReference(), $salesPaymentDetailEntity->getPaymentReference());
        $this->assertSame($salesPaymentDetailTransfer->getEntityReference(), $salesPaymentDetailEntity->getEntityReference());
        $this->assertSame($salesPaymentDetailTransfer->getDetails(), $salesPaymentDetailEntity->getDetails());
    }
}
