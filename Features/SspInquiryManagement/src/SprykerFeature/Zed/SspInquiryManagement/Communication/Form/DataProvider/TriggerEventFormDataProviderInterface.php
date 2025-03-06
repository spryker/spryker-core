<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider;

interface TriggerEventFormDataProviderInterface
{
    /**
     * @param int $idSspInquiry
     *
     * @return array<mixed>
     */
    public function getOptions(int $idSspInquiry): array;
}
