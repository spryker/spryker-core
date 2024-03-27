<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface MerchantRelationTableUrlBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return string|null
     */
    public function buildMerchantRelationTableUrl(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): ?string;
}
