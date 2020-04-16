<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SalesReturn\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReason;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SalesReturnHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param string[] $glossaryKeyReasons
     *
     * @return \Generated\Shared\Transfer\ReturnReasonTransfer[]
     */
    public function haveReturnReasons(array $glossaryKeyReasons): array
    {
        $returnReasonTransfers = [];

        foreach ($glossaryKeyReasons as $glossaryKeyReason) {
            $salesReturnReasonEntity = (new SpySalesReturnReason())
                ->setGlossaryKeyReason($glossaryKeyReason);

            $salesReturnReasonEntity->save();

            $returnReasonTransfers[] = (new ReturnReasonTransfer())->fromArray($salesReturnReasonEntity->toArray(), true);
        }

        return $returnReasonTransfers;
    }
}
