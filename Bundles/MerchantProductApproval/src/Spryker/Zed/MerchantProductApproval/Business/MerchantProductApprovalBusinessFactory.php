<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductApproval\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductApproval\Business\Expander\MerchantProductApprovalProductAbstractExpander;
use Spryker\Zed\MerchantProductApproval\Business\Expander\MerchantProductApprovalProductAbstractExpanderInterface;
use Spryker\Zed\MerchantProductApproval\Dependency\Facade\MerchantProductApprovalToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductApproval\MerchantProductApprovalDependencyProvider;

class MerchantProductApprovalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductApproval\Business\Expander\MerchantProductApprovalProductAbstractExpanderInterface
     */
    public function createMerchantProductApprovalProductAbstractExpander(): MerchantProductApprovalProductAbstractExpanderInterface
    {
        return new MerchantProductApprovalProductAbstractExpander($this->getMerchantProductFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProductApproval\Dependency\Facade\MerchantProductApprovalToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): MerchantProductApprovalToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductApprovalDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }
}
