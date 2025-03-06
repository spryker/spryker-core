<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider;

use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\SspInquiryFilterForm;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryFilterFormDataProvider implements SspInquiryFilterFormDataProviderInterface
{
    /**
     * @var \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig
     */
    protected SspInquiryManagementConfig $sspInquiryManagementConfig;

    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(SspInquiryManagementConfig $sspInquiryManagementConfig)
    {
        $this->sspInquiryManagementConfig = $sspInquiryManagementConfig;
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
         $sspInquiryStatuses = array_keys($this->sspInquiryManagementConfig->getSspInquiryStatusClassMap());
         $sspInquiryTypes = $this->sspInquiryManagementConfig->getAllSelectableSspInquiryTypes();

        return [
            SspInquiryFilterForm::OPTION_STATUSES => array_combine($sspInquiryStatuses, $sspInquiryStatuses),
            SspInquiryFilterForm::OPTION_TYPES => array_combine($sspInquiryTypes, $sspInquiryTypes),
        ];
    }
}
