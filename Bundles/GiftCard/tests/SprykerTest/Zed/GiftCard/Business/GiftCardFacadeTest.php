<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\GiftCardBuilder;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group GiftCardFacadeTest
 * Add your own group annotations below this line
 */
class GiftCardFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindByIdShouldReturnTransferObjectForExistingGiftCard()
    {
        $giftCardTransfer = $this->tester->haveGiftCard(['attributes' => []]);

        $foundGiftCardTransfer = $this->getFacade()->findById($giftCardTransfer->getIdGiftCard());

        $this->assertNotNull($foundGiftCardTransfer);
        $this->assertSame($giftCardTransfer->getIdGiftCard(), $foundGiftCardTransfer->getIdGiftCard());
    }

    /**
     * @return void
     */
    public function testCreateShouldAssertRequiredTransferObjectFields()
    {
        $giftCardTransfer = (new GiftCardBuilder([
            'attributes' => [],
            'value' => null,
        ]))->build();

        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessageRegExp('/^Missing required property "value" for transfer/');
        $this->getFacade()->create($giftCardTransfer);
    }

    /**
     * @return void
     */
    public function testCreateShouldPersistGiftCard()
    {
        $giftCardTransfer = (new GiftCardBuilder([
            'attributes' => [],
            'idGiftCard' => null,
        ]))->build();
        $this->getFacade()->create($giftCardTransfer);

        $this->assertNotNull($giftCardTransfer->getIdGiftCard());

        $createdGiftCardTransfer = $this->getFacade()->findById($giftCardTransfer->getIdGiftCard());

        $this->assertSame($giftCardTransfer->getCode(), $createdGiftCardTransfer->getCode());
        $this->assertSame($giftCardTransfer->getName(), $createdGiftCardTransfer->getName());
        $this->assertEquals($giftCardTransfer->getValue(), $createdGiftCardTransfer->getValue());
        $this->assertEquals($giftCardTransfer->getIsActive(), $createdGiftCardTransfer->getIsActive());
    }

    /**
     * @return \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
