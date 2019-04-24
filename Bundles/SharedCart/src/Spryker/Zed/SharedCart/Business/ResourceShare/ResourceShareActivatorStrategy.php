<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface;

class ResourceShareActivatorStrategy implements ResourceShareActivatorStrategyInterface
{
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.cart_access_denied';

    protected const QUOTE_RESOURCE_TYPE = 'Quote';

    protected const KEY_SHARE_OPTION = 'share_option';

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface $customerFacade
     */
    public function __construct(SharedCartToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isResourceShareActivatorStrategyApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if ($resourceShareTransfer->getResourceType() !== static::QUOTE_RESOURCE_TYPE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $resourceShareOption = $this->findShareOption($resourceShareDataTransfer);

        return $resourceShareOption && $this->isShareOptionSupported($resourceShareOption);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function applyResourceShareActivatorStrategy(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer->requireResourceShare();
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();

        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $idCompanyBusinessUnit = $resourceShareDataTransfer->getIdCompanyBusinessUnit();
        if (!$idCompanyBusinessUnit) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        $resourceShareRequestTransfer->requireCustomer();
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();

        if ($this->findIdCompanyBusinessUnit($customerTransfer) !== $idCompanyBusinessUnit) {
            return $resourceShareResponseTransfer->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_ACCESS_DENIED)
                );
        }

        // TODO: Share cart with logged in customer

        return $resourceShareResponseTransfer->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int|null
     */
    protected function findIdCompanyBusinessUnit(CustomerTransfer $customerTransfer): ?int
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return null;
        }

        $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        return $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
    }

    /**
     * @param string $shareOption
     *
     * @return bool
     */
    protected function isShareOptionSupported(string $shareOption): bool
    {
        return in_array($shareOption, [SharedCartConfig::PERMISSION_GROUP_READ_ONLY, SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS], true);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return string|null
     */
    protected function findShareOption(ResourceShareDataTransfer $resourceShareDataTransfer): ?string
    {
        $resourceShareData = $resourceShareDataTransfer->getData();

        return $resourceShareData[static::KEY_SHARE_OPTION] ?? null;
    }
}
