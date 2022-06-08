<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Payment\Extractor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Payment
 * @group Extractor
 * @group PaymentMethodKeyExtractorTest
 * Add your own group annotations below this line
 */
class PaymentMethodKeyExtractorTest extends Unit
{
    /**
     * @var \SprykerTest\Service\Payment\PaymentServiceTester
     */
    protected $tester;

    /**
     * @dataProvider getPaymentSelectionKeyCollectionDataProvider
     *
     * @param string $expectedKey
     * @param string|null $inputKey
     *
     * @return void
     */
    public function testGetPaymentSelectionKeyReturnsCorrectResponseWhenInputDataAreCorrect(
        string $expectedKey,
        ?string $inputKey = null
    ): void {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection($inputKey);

        // Act
        $key = $this->tester->getService()->getPaymentSelectionKey($paymentTransfer);

        // Assert
        $this->assertEquals($expectedKey, $key);
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    public function getPaymentSelectionKeyCollectionDataProvider(): array
    {
        return [
            ['www', 'www[qwe]'], ['w1w2w', 'w1w2w(qwe)'], ['t_es_t', 't_es_t{qwe}'], ['www', 'www[(qwe)]'], ['', ''], ['', null],
        ];
    }

    /**
     * @dataProvider getPaymentMethodKeyCollectionDataProvider
     *
     * @param string $expectedKey
     * @param string|null $inputKey
     *
     * @return void
     */
    public function testGetPaymentMethodKeyReturnsCorrectResponseWhenInputDataAreCorrect(
        string $expectedKey,
        ?string $inputKey = null
    ): void {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection($inputKey);

        // Act
        $key = $this->tester->getService()->getPaymentMethodKey($paymentTransfer);

        // Assert
        $this->assertEquals($expectedKey, $key);
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    public function getPaymentMethodKeyCollectionDataProvider(): array
    {
        return [
            ['qwe', 'www[qwe]'], ['w_w_w', 'test[w_w_w]'], ['q1w2e', 't_es_t[q1w2e]'], ['', ''], ['', null],
        ];
    }
}
