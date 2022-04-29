<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class VoucherDataProvider implements FormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        if ($transfer->getManualOrder() === null) {
            $transfer->setManualOrder(new ManualOrderTransfer());
        }

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array<string, mixed>
     */
    public function getOptions($transfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
