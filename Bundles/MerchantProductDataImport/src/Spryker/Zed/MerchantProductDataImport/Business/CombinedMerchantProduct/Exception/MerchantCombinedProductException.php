<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

class MerchantCombinedProductException extends DataImportException
{
    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     *
     * @return self
     */
    public static function createWithError(ErrorTransfer $errorTransfer): self
    {
        $dataImportException = new self($errorTransfer->getMessageOrFail());

        if (method_exists($dataImportException, 'setError') === true) {
            $dataImportException->setError($errorTransfer);
        }

        return $dataImportException;
    }
}
