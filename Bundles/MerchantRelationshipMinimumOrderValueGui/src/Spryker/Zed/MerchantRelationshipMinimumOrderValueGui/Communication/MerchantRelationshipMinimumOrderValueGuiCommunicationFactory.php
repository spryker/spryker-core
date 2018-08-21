<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Table\MerchantRelationshipMinimumOrderValueTable;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig getConfig()
 */
class MerchantRelationshipMinimumOrderValueGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Table\MerchantRelationshipMinimumOrderValueTable
     */
    public function createMerchantRelationshipMinimumOrderValueTable(): MerchantRelationshipMinimumOrderValueTable
    {
        return new MerchantRelationshipMinimumOrderValueTable();
    }
}
