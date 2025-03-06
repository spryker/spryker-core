<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Mapper;

use Generated\Shared\Transfer\SspInquiryTransfer;

interface CreateSspInquiryFormDataToTransferMapperInterface
{
    /**
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function mapSspInquiryData(array $formData): SspInquiryTransfer;
}
