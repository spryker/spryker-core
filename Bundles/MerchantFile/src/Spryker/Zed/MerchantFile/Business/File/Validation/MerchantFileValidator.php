<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\File\Validation;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\MerchantFile\Business\File\Validation\Validator\ValidatorInterface;

class MerchantFileValidator implements MerchantFileValidatorInterface
{
    /**
     * @param \Spryker\Zed\MerchantFile\Business\File\Validation\Validator\ValidatorInterface $fileContentTypeValidator
     * @param array<\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFileValidationPluginInterface> $merchantFileValidatorPlugins
     */
    public function __construct(
        protected ValidatorInterface $fileContentTypeValidator,
        protected array $merchantFileValidatorPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function validateMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileResultTransfer
    {
        $merchantFileResultTransfer = (new MerchantFileResultTransfer())->setIsSuccessful(true);

        $merchantFileResultTransfer = $this->fileContentTypeValidator->validate($merchantFileTransfer, $merchantFileResultTransfer);

        if (!$merchantFileResultTransfer->getIsSuccessful()) {
            return $merchantFileResultTransfer;
        }

        foreach ($this->merchantFileValidatorPlugins as $validatorPlugin) {
            $merchantFileResultTransfer = $validatorPlugin->validate($merchantFileTransfer, $merchantFileResultTransfer);
        }

        return $merchantFileResultTransfer;
    }
}
