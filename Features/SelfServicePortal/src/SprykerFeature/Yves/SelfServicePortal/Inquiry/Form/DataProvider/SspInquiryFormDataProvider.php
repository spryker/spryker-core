<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider;

use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquiryForm;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryFormDataProvider
{
    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            SspInquiryForm::OPTION_SSP_INQUIRY_TYPE_CHOICES => $this->selfServicePortalConfig->getSelectableSspInquiryTypes(),
            SspInquiryForm::OPTION_ALLOWED_EXTENSIONS => $this->selfServicePortalConfig->getSspInquiryAllowedFileExtensions(),
            SspInquiryForm::OPTION_ALLOWED_MIME_TYPES => $this->selfServicePortalConfig->getCompanyFilesAllowedFileTypes(),
        ];
    }
}
