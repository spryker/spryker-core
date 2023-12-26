<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\TaxApp\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\DeleteTaxAppTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;

class TaxCommandsDeleteTaxAppHelper extends Module
{
    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\DeleteTaxAppTransfer
     */
    public function haveDeleteTaxAppMessage(array $seed = []): DeleteTaxAppTransfer
    {
        $deleteTaxAppTransfer = new DeleteTaxAppTransfer();
        $deleteTaxAppTransfer
            ->fromArray($seed)
            ->setMessageAttributes(new MessageAttributesTransfer());

        return $deleteTaxAppTransfer;
    }
}
