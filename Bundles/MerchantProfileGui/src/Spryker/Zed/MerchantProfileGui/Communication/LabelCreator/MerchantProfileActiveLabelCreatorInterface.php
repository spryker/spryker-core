<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\LabelCreator;

interface MerchantProfileActiveLabelCreatorInterface
{
    /**
     * @param int $idMerchant
     *
     * @return string
     */
    public function getActiveLabel(int $idMerchant): string;
}
