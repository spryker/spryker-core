<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;

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
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantProfile\PHPMD)
 */
class MerchantProfileCommunicationTester extends Actor
{
    use _generated\MerchantProfileCommunicationTesterActions;

    /**
     * @param array<mixed> $seed
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function haveQuoteTransfer(array $seed = []): CalculableObjectTransfer
    {
        $calculableObjectTransfer = (new CalculableObjectBuilder())->seed($seed)->build();

        if (!isset($seed['items']) || !is_array($seed['items'])) {
            return $calculableObjectTransfer;
        }

        return $calculableObjectTransfer->setItems($this->getItems($seed['items']));
    }

    /**
     * @param array<mixed> $seed
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function haveOrderTransfer(array $seed = []): OrderTransfer
    {
        $orderTransfer = (new OrderBuilder())->seed($seed)->build();

        if (!isset($seed['items']) || !is_array($seed['items'])) {
            return $orderTransfer;
        }

        return $orderTransfer->setItems($this->getItems($seed['items']));
    }

    /**
     * @param array<int, mixed> $seed
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItems(array $seed): ArrayObject
    {
        $items = [];

        foreach ($seed as $itemSeed) {
            $items[] = (new ItemBuilder())->seed($itemSeed)->build();
        }

        return new ArrayObject($items);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer | \Generated\Shared\Transfer\CalculableObjectTransfer $expandedTransfer
     *
     * @return void
     */
    public function assertItemTransfersAreExpandedWithMerchantProfileAddress(OrderTransfer|CalculableObjectTransfer $expandedTransfer): void
    {
        $orderItemTransfer1 = $expandedTransfer->getItems()->offsetGet(0);
        $orderItemTransfer2 = $expandedTransfer->getItems()->offsetGet(1);

        $merchantProfileAddressTransfer1 = $orderItemTransfer1->getMerchantProfileAddress();
        $merchantProfileAddressTransfer2 = $orderItemTransfer2->getMerchantProfileAddress();

        $this->assertNotNull($merchantProfileAddressTransfer1);
        $this->assertNotNull($merchantProfileAddressTransfer1->getAddress1());
        $this->assertNotNull($merchantProfileAddressTransfer1->getCity());
        $this->assertNotNull($merchantProfileAddressTransfer1->getZipCode());

        $this->assertNotNull($merchantProfileAddressTransfer2);
        $this->assertNotNull($merchantProfileAddressTransfer2->getAddress1());
        $this->assertNotNull($merchantProfileAddressTransfer2->getCity());
        $this->assertNotNull($merchantProfileAddressTransfer2->getZipCode());
    }
}
