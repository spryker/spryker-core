<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper;

use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryNodePageSearchDataMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapCategoryNodeDataToSearchData(array $data, LocaleTransfer $localeTransfer): array;
}
