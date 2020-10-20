<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCategoryTransfer;

class MerchantCategorySearchToMerchantCategoryFacadeBridge implements MerchantCategorySearchToMerchantCategoryFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacadeInterface
     */
    protected $merchantCategoryFacade;

    /**
     * @param \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacadeInterface $merchantCategoryFacade
     */
    public function __construct($merchantCategoryFacade)
    {
        $this->merchantCategoryFacade = $merchantCategoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryTransfer
     */
    public function get(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): MerchantCategoryTransfer
    {
        return $this->merchantCategoryFacade->get($merchantCategoryCriteriaTransfer);
    }
}
