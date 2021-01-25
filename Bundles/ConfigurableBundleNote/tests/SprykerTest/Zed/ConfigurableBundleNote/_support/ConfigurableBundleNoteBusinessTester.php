<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleNote;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ConfigurableBundleNote\Business\ConfigurableBundleNoteFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigurableBundleNoteBusinessTester extends Actor
{
    use _generated\ConfigurableBundleNoteBusinessTesterActions;

    public const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';
    public const FAKE_CONFIGURABLE_BUNDLE_NOTE = 'Configurable Bundle Note';
    public const FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY = 'configurable-bundle-group-key';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->buildCustomerTransfer(static::FAKE_CUSTOMER_REFERENCE),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithConfiguredBundle(): QuoteTransfer
    {
        return $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->buildCustomerTransfer(static::FAKE_CUSTOMER_REFERENCE),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONFIGURED_BUNDLE => $this->buildConfiguredBundleTransfer(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY),
                ],
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONFIGURED_BUNDLE => $this->buildConfiguredBundleTransfer(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY),
                ],
            ],
        ]);
    }

    /**
     * @param string|null $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function buildCustomerTransfer(?string $customerReference = null): CustomerTransfer
    {
        return (new CustomerBuilder())->build()
            ->setCustomerReference($customerReference);
    }

    /**
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function buildConfiguredBundleTransfer(?string $groupKey = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setGroupKey($groupKey);
    }
}
