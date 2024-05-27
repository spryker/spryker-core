<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;

class MerchantCommissionImportValidator implements MerchantCommissionImportValidatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionValidatorInterface
     */
    protected MerchantCommissionValidatorInterface $merchantCommissionCreateValidator;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionValidatorInterface
     */
    protected MerchantCommissionValidatorInterface $merchantCommissionUpdateValidator;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionValidatorInterface $merchantCommissionCreateValidator
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\MerchantCommissionValidatorInterface $merchantCommissionUpdateValidator
     */
    public function __construct(
        MerchantCommissionValidatorInterface $merchantCommissionCreateValidator,
        MerchantCommissionValidatorInterface $merchantCommissionUpdateValidator
    ) {
        $this->merchantCommissionCreateValidator = $merchantCommissionCreateValidator;
        $this->merchantCommissionUpdateValidator = $merchantCommissionUpdateValidator;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $newMerchantCommissionTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $existingMerchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function validate(
        ArrayObject $newMerchantCommissionTransfers,
        ArrayObject $existingMerchantCommissionTransfers
    ): MerchantCommissionCollectionResponseTransfer {
        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setIsTransactional(false);

        $merchantCommissionCollectionCreateResponseTransfer = $this->merchantCommissionCreateValidator->validate(
            $merchantCommissionCollectionRequestTransfer->setMerchantCommissions($newMerchantCommissionTransfers),
        );
        $merchantCommissionCollectionUpdateResponseTransfer = $this->merchantCommissionUpdateValidator->validate(
            $merchantCommissionCollectionRequestTransfer->setMerchantCommissions($existingMerchantCommissionTransfers),
        );

        return $this->mergeMerchantCommissionCollectionResponseTransfers(
            $merchantCommissionCollectionCreateResponseTransfer,
            $merchantCommissionCollectionUpdateResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionCreateResponseTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionUpdateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    protected function mergeMerchantCommissionCollectionResponseTransfers(
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionCreateResponseTransfer,
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionUpdateResponseTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        $merchantCommissionResponseCollectionTransfer = new MerchantCommissionCollectionResponseTransfer();
        $merchantCommissionCreateErrorTransfers = $merchantCommissionCollectionCreateResponseTransfer->getErrors();
        $merchantCommissionUpdateErrorTransfers = $merchantCommissionCollectionUpdateResponseTransfer->getErrors();

        if (
            $merchantCommissionCreateErrorTransfers->count() === 0
            && $merchantCommissionUpdateErrorTransfers->count() === 0
        ) {
            return $merchantCommissionResponseCollectionTransfer;
        }

        $merchantCommissionResponseCollectionTransfer->setErrors($merchantCommissionCreateErrorTransfers);
        foreach ($merchantCommissionUpdateErrorTransfers as $errorTransfer) {
            $merchantCommissionResponseCollectionTransfer->addError($errorTransfer);
        }

        return $merchantCommissionResponseCollectionTransfer;
    }
}
