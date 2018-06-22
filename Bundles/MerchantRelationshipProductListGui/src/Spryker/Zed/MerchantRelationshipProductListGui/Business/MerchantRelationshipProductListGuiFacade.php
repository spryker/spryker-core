<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Business\MerchantRelationshipProductListGuiBusinessFactory getFactory()
 */
class MerchantRelationshipProductListGuiFacade extends AbstractFacade implements MerchantRelationshipProductListGuiFacadeInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function listMerchantRelation(): array
    {
        return $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->listMerchantRelation();
    }
}
