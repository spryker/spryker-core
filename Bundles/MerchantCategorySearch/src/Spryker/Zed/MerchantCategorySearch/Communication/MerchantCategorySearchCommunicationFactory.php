<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface;
use Spryker\Zed\MerchantCategorySearch\MerchantCategorySearchDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantCategorySearch\MerchantCategorySearchConfig getConfig()
 */
class MerchantCategorySearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface
     */
    public function getMerchantCategoryFacade(): MerchantCategorySearchToMerchantCategoryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCategorySearchDependencyProvider::FACADE_MERCHANT_CATEGORY);
    }
}
