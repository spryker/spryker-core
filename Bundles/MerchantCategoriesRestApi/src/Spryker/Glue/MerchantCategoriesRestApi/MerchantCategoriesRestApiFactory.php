<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantCategoriesRestApi\Processor\Mapper\MerchantCategoryMapper;
use Spryker\Glue\MerchantCategoriesRestApi\Processor\Mapper\MerchantCategoryMapperInterface;

class MerchantCategoriesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantCategoriesRestApi\Processor\Mapper\MerchantCategoryMapperInterface
     */
    public function createMerchantCategoryMapper(): MerchantCategoryMapperInterface
    {
        return new MerchantCategoryMapper();
    }
}
