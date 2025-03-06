<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form\DataProvider;

use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquiryForm;
use SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryFormDataProvider
{
    /**
     * @param \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(protected SspInquiryManagementConfig $sspInquiryManagementConfig)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            SspInquiryForm::OPTION_SSP_INQUIRY_TYPE_CHOICES => $this->sspInquiryManagementConfig->getSelectableSspInquiryTypes(),
            SspInquiryForm::OPTION_ALLOWED_EXTENSIONS => $this->sspInquiryManagementConfig->getAllowedFileExtensions(),
            SspInquiryForm::OPTION_ALLOWED_MIME_TYPES => $this->sspInquiryManagementConfig->getAllowedFileMimeTypes(),
        ];
    }
}
