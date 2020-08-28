<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade;

interface MerchantProfileMerchantPortalGuiToLocaleFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection();
}
