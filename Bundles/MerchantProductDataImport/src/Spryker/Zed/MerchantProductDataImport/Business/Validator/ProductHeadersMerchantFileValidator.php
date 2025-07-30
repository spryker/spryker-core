<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\Validator;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class ProductHeadersMerchantFileValidator implements MerchantFileValidatorInterface
{
    /**
     * @var array<string>
     */
    protected const REQUIRED_HEADERS = [
        'abstract_sku',
        'product.assigned_product_type',
    ];

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_REQUIRED_HEADER = 'The required field %header% is missing';

    /**
     * @var string
     */
    protected const PARAM_HEADER = '%header%';

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     * @param \Generated\Shared\Transfer\MerchantFileResultTransfer $merchantFileResultTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function validate(
        MerchantFileTransfer $merchantFileTransfer,
        MerchantFileResultTransfer $merchantFileResultTransfer
    ): MerchantFileResultTransfer {
        [$rawHeaders] = explode(PHP_EOL, $merchantFileTransfer->getContentOrFail());

        $headers = str_getcsv($rawHeaders);

        foreach (static::REQUIRED_HEADERS as $requiredHeader) {
            if (!in_array($requiredHeader, $headers, true)) {
                $merchantFileResultTransfer
                    ->setIsSuccessful(false)
                    ->addMessage($this->createMissingRequiredHeaderMessage($requiredHeader));

                return $merchantFileResultTransfer;
            }
        }

        return $merchantFileResultTransfer;
    }

    /**
     * @param string $header
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMissingRequiredHeaderMessage(string $header): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::ERROR_MESSAGE_MISSING_REQUIRED_HEADER)
            ->setParameters([static::PARAM_HEADER => $header]);
    }
}
