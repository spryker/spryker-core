<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteCheckoutConnector\Business\QuoteCheckoutCondition;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Zed\QuoteCheckoutConnector\Dependency\Client\QuoteCheckoutConnectorToStorageRedisClientInterface;
use Spryker\Zed\QuoteCheckoutConnector\Dependency\Service\QuoteCheckoutConnectorToUtilTextServiceInterface;
use Spryker\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorConfig;

class QuoteCheckoutCondition implements QuoteCheckoutConditionInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_DUPLICATE_ORDER_PROCESSING = 'checkout.error.duplicate-order-processing';

    /**
     * @var string
     */
    protected const DUPLICATE_ORDER_LOCKED_QUOTE_ID_PARAMETER = '%quote-uid%';

    /**
     * @var string
     */
    protected const LOCK_KEY_PLACEHOLDER = '%s:%s';

    /**
     * @var string
     */
    protected const GUEST_HASH_VALUE_PLACEHOLDER = '%s-%s-%s';

    /**
     * @var \Spryker\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorConfig
     */
    protected QuoteCheckoutConnectorConfig $config;

    /**
     * @var \Spryker\Zed\QuoteCheckoutConnector\Dependency\Client\QuoteCheckoutConnectorToStorageRedisClientInterface
     */
    protected QuoteCheckoutConnectorToStorageRedisClientInterface $storageRedisClient;

    /**
     * @var \Spryker\Zed\QuoteCheckoutConnector\Dependency\Service\QuoteCheckoutConnectorToUtilTextServiceInterface
     */
    protected QuoteCheckoutConnectorToUtilTextServiceInterface $utilTextService;

    /**
     * @param \Spryker\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorConfig $config
     * @param \Spryker\Zed\QuoteCheckoutConnector\Dependency\Client\QuoteCheckoutConnectorToStorageRedisClientInterface $storageRedisClient
     * @param \Spryker\Zed\QuoteCheckoutConnector\Dependency\Service\QuoteCheckoutConnectorToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        QuoteCheckoutConnectorConfig $config,
        QuoteCheckoutConnectorToStorageRedisClientInterface $storageRedisClient,
        QuoteCheckoutConnectorToUtilTextServiceInterface $utilTextService
    ) {
        $this->config = $config;
        $this->storageRedisClient = $storageRedisClient;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function disallowCheckoutForQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->storageRedisClient->set($this->getLockKey($quoteTransfer), 'true', $this->config->getTtlQuoteCheckoutLock());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isCheckoutAllowedForQuote(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ((bool)$this->storageRedisClient->get($this->getLockKey($quoteTransfer))) {
            $this->addErrorToCheckoutResponseTransfer($quoteTransfer, $checkoutResponseTransfer);

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getLockKey(QuoteTransfer $quoteTransfer): string
    {
        return sprintf(
            static::LOCK_KEY_PLACEHOLDER,
            $this->config->getQuoteCheckoutLockStorageNamespace(),
            $quoteTransfer->getUuid() ?? $this->buildGuestUniqueId($quoteTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function buildGuestUniqueId(QuoteTransfer $quoteTransfer): string
    {
        $customerTransfer = $quoteTransfer->getCustomerOrFail();

        $hashValue = sprintf(
            static::GUEST_HASH_VALUE_PLACEHOLDER,
            $customerTransfer->getFirstName(),
            $customerTransfer->getLastName(),
            $customerTransfer->getEmail(),
        );

        return $this->utilTextService->hashValue($hashValue, Hash::MD5);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function addErrorToCheckoutResponseTransfer(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer($quoteTransfer);
        $checkoutResponseTransfer->addError($checkoutErrorTransfer)->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(QuoteTransfer $quoteTransfer): CheckoutErrorTransfer
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage(static::GLOSSARY_KEY_DUPLICATE_ORDER_PROCESSING);
        $checkoutErrorTransfer->setParameters([static::DUPLICATE_ORDER_LOCKED_QUOTE_ID_PARAMETER => $quoteTransfer->getUuid()]);

        return $checkoutErrorTransfer;
    }
}
