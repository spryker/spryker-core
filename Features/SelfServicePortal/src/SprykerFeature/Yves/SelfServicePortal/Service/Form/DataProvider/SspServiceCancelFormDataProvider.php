<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SspServiceCancelForm;

class SspServiceCancelFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function getData(?ItemTransfer $itemTransfer = null): array
    {
        if (!$itemTransfer) {
            return [];
        }

        return [
            SspServiceCancelForm::FIELD_ITEM_UUID => $itemTransfer->getUuid(),
            SspServiceCancelForm::FIELD_ID_SALES_ORDER => $itemTransfer->getFkSalesOrder(),
        ];
    }
}
