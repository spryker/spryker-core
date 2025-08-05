<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider;

use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\SspInquiryFilterForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryFilterFormDataProvider implements SspInquiryFilterFormDataProviderInterface
{
    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
    {
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        $sspInquiryStatuses = array_keys($this->selfServicePortalConfig->getInquiryStatusClassMap());
        $sspInquiryTypes = array_merge(...array_values($this->selfServicePortalConfig->getSelectableSspInquiryTypes()));

        return [
            SspInquiryFilterForm::OPTION_STATUSES => array_combine($sspInquiryStatuses, $sspInquiryStatuses),
            SspInquiryFilterForm::OPTION_TYPES => array_combine($sspInquiryTypes, $sspInquiryTypes),
        ];
    }
}
