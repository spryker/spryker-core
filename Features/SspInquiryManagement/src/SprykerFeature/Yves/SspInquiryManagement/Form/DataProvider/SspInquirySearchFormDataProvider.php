<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form\DataProvider;

use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquirySearchForm;
use SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquirySearchFormDataProvider
{
    /**
     * @param \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     * @param string|null $currentTimezone
     */
    public function __construct(
        protected SspInquiryManagementConfig $sspInquiryManagementConfig,
        protected ?string $currentTimezone
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $mappedTypes = array_combine(array_map(fn ($type) => 'ssp_inquiry.type.' . $type, $this->sspInquiryManagementConfig->getAllSelectableSspInquiryTypes()), $this->sspInquiryManagementConfig->getAllSelectableSspInquiryTypes());
        $mappedStatuses = array_combine(array_map(fn ($status) => 'ssp_inquiry.status.' . $status, $this->sspInquiryManagementConfig->getAvailableStatuses()), $this->sspInquiryManagementConfig->getAvailableStatuses());

        return [
            SspInquirySearchForm::OPTION_SSP_INQUIRY_TYPES => $mappedTypes,
            SspInquirySearchForm::OPTION_SSP_INQUIRY_STATUSES => $mappedStatuses,
            SspInquirySearchForm::OPTION_CURRENT_TIMEZONE => $this->currentTimezone,
        ];
    }
}
