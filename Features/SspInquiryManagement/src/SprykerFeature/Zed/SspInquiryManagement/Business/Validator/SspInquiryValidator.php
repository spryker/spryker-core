<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryValidator implements SspInquiryValidatorInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(protected SspInquiryManagementConfig $sspInquiryManagementConfig)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateSspInquiry(SspInquiryTransfer $sspInquiryTransfer): ArrayObject
    {
        $validationErrors = new ArrayObject();

        if (!$sspInquiryTransfer->getCompanyUser() || !$sspInquiryTransfer->getCompanyUser()->getIdCompanyUser()) {
            $validationErrors->append(
                (new ErrorTransfer())->setMessage('ssp_inquiry.validation.company_user.not_set'),
            );
        }

        if (!$sspInquiryTransfer->getType()) {
            $validationErrors->append(
                (new ErrorTransfer())->setMessage('ssp_inquiry.validation.type.not_set'),
            );
        }

        if ($sspInquiryTransfer->getType() && !in_array($sspInquiryTransfer->getType(), $this->sspInquiryManagementConfig->getAllSelectableSspInquiryTypes())) {
            $validationErrors->append(
                (new ErrorTransfer())->setMessage('ssp_inquiry.validation.type.invalid'),
            );
        }

        if (!$sspInquiryTransfer->getSubject()) {
            $validationErrors->append(
                (new ErrorTransfer())->setMessage('ssp_inquiry.validation.subject.not_set'),
            );
        }

        if (!$sspInquiryTransfer->getDescription()) {
            $validationErrors->append(
                (new ErrorTransfer())->setMessage('ssp_inquiry.validation.description.not_set'),
            );
        }

        return $validationErrors;
    }
}
