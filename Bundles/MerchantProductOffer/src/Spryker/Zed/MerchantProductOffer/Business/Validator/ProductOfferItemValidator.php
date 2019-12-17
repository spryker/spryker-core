<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface;

class ProductOfferItemValidator implements ProductOfferItemValidatorInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const GLOSSARY_KEY_INACTIVE_MERCHANT_PROFILE = 'merchant_profile.message.inactive';
    protected const GLOSSARY_KEY_REMOVED_MERCHANT_PROFILE = 'merchant_profile.message.removed';

    protected const GLOSSARY_PARAM_SKU = '%sku%';
    protected const GLOSSARY_PARAM_MERCHANT_NAME = '%merchant_name%';

    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantProductOfferToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function validateItems(array $itemTransfers): array
    {
        $messageTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getProductOffer()) {
                continue;
            }

            $merchantTransfer = $this->merchantFacade->findOne(
                (new MerchantCriteriaFilterTransfer())
                    ->setMerchantReference($itemTransfer->getMerchantReference())
            );

            if ($merchantTransfer &&
                $merchantTransfer->getMerchantProfile() &&
                $merchantTransfer->getMerchantProfile()->getIsActive()
            ) {
                continue;
            }

            $messageTransfers[] = $this->createErrorMessageTransfer($itemTransfer, $merchantTransfer->getMerchantProfile());
        }

        return $messageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer|null $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createErrorMessageTransfer(ItemTransfer $itemTransfer, ?MerchantProfileTransfer $merchantProfileTransfer): MessageTransfer
    {
        if (!$merchantProfileTransfer) {
            return (new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_ERROR)
                ->setValue(static::GLOSSARY_KEY_REMOVED_MERCHANT_PROFILE)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku()]);
        }

        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_INACTIVE_MERCHANT_PROFILE)
            ->setParameters([
                static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku(),
                static::GLOSSARY_PARAM_MERCHANT_NAME => $merchantProfileTransfer->getMerchantName(),
            ]);
    }
}
