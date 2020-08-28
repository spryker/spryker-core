<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\DataMapper;

use Generated\Shared\Transfer\LocaleTransfer;

interface ReturnReasonSearchDataMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapReturnReasonDataToSearchData(array $data, LocaleTransfer $localeTransfer): array;
}
