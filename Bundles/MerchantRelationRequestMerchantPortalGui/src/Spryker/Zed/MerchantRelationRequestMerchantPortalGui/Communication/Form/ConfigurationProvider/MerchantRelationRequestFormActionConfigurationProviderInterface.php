<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\ConfigurationProvider;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface MerchantRelationRequestFormActionConfigurationProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return list<array<string>>
     */
    public function getActions(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array;
}
