<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\DataMapper;

interface MerchantSearchDataMapperInterface
{
    /**
     * @param array<string> $data
     *
     * @return array<mixed>
     */
    public function mapMerchantDataToSearchData(array $data): array;
}
