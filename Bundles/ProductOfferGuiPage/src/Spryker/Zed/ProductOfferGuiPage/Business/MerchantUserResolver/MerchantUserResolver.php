<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface;

class MerchantUserResolver implements MerchantUserResolverInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        ProductOfferGuiPageToUserFacadeInterface $userFacade,
        ProductOfferGuiPageToMerchantUserFacadeInterface $merchantUserFacade
    ) {

        $this->userFacade = $userFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findCurrentMerchantUser(): ?MerchantUserTransfer
    {
        $currentUserTransfer = $this->userFacade->getCurrentUser();
        $merchantUserCriteriaFilterTransfer = new MerchantUserCriteriaFilterTransfer();
        $merchantUserCriteriaFilterTransfer->setIdUser($currentUserTransfer->getIdUser());

        return $this->merchantUserFacade->findOne($merchantUserCriteriaFilterTransfer);
    }
}
