<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

class MerchantCombinedProductOfferException extends DataImportException
{
    public static function createWithError(ErrorTransfer $errorTransfer): self
    {
        $dataImportException = new self($errorTransfer->getMessageOrFail());

        if (method_exists($dataImportException, 'setError') === true) {
            $dataImportException->setError($errorTransfer);
        }

        return $dataImportException;
    }
}
