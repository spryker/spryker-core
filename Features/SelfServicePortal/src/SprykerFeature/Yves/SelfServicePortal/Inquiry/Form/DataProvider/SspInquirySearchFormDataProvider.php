<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider;

use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquirySearchForm;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class SspInquirySearchFormDataProvider
{
    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected ?string $currentTimezone
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $allSelectableSspInquiryTypes = array_merge(...array_values($this->selfServicePortalConfig->getSelectableSspInquiryTypes()));
        $mappedTypes = array_combine(array_map(fn ($type) => 'self_service_portal.inquiry.type.' . $type, $allSelectableSspInquiryTypes), $allSelectableSspInquiryTypes);
        $mappedStatuses = array_combine(array_map(fn ($status) => 'self_service_portal.inquiry.status.' . $status, $this->selfServicePortalConfig->getSspInquiryAvailableStatuses()), $this->selfServicePortalConfig->getSspInquiryAvailableStatuses());

        return [
            SspInquirySearchForm::OPTION_SSP_INQUIRY_TYPES => $mappedTypes,
            SspInquirySearchForm::OPTION_SSP_INQUIRY_STATUSES => $mappedStatuses,
            SspInquirySearchForm::OPTION_CURRENT_TIMEZONE => $this->currentTimezone,
        ];
    }
}
