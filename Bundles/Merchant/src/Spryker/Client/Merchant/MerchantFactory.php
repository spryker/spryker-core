<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Merchant;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Merchant\Mapper\MerchantShoppingListItemToItemMapper;
use Spryker\Client\Merchant\Mapper\MerchantShoppingListItemToItemMapperInterface;

/**
 * @method \Spryker\Client\Merchant\MerchantConfig getConfig()
 */
class MerchantFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Merchant\Mapper\MerchantShoppingListItemToItemMapperInterface
     */
    public function createMerchantShoppingListItemToItemMapper(): MerchantShoppingListItemToItemMapperInterface
    {
        return new MerchantShoppingListItemToItemMapper();
    }
}
