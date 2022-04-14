<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Payment\Extractor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Service\Payment\Extractor\PaymentMethodKeyExtractor;

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
     * @var \SprykerTest\Service\Payment\PaymentMethodKeyExtractorInterface
     */
    protected $paymentService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentService = new PaymentMethodKeyExtractor();
    }

    /**
     * @dataProvider getPaymentSelectionKeyCollectionDataProvider
     *
     * @param string $inputKey
     * @param string $expectedKey
     *
     * @return void
     */
    public function testGetPaymentSelectionKeyReturnsCorrectResponseWhenInputDataAreCorrect(
        string $inputKey,
        string $expectedKey
    ): void {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection($inputKey);

        // Act
        $key = $this->paymentService->getPaymentSelectionKey($paymentTransfer);

        // Assert
        $this->assertEquals($expectedKey, $key);
    }

    /**
     * @return array
     */
    public function getPaymentSelectionKeyCollectionDataProvider(): array
    {
        return [
            ['www[qwe]', 'www'], ['w1w2w(qwe)', 'w1w2w'], ['t_es_t{qwe}', 't_es_t'], ['www[(qwe)]', 'www'], ['', ''],
        ];
    }

    /**
     * @dataProvider getPaymentMethodKeyCollectionDataProvider
     *
     * @param string $inputKey
     * @param string $expectedKey
     *
     * @return void
     */
    public function testGetPaymentMethodKeyReturnsCorrectResponseWhenInputDataAreCorrect(
        string $inputKey,
        string $expectedKey
    ): void {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection($inputKey);

        // Act
        $key = $this->paymentService->getPaymentMethodKey($paymentTransfer);

        // Assert
        $this->assertEquals($expectedKey, $key);
    }

    /**
     * @return array
     */
    public function getPaymentMethodKeyCollectionDataProvider(): array
    {
        return [
            ['www[qwe]', 'qwe'], ['test[w_w_w]', 'w_w_w'], ['t_es_t[q1w2e]', 'q1w2e'], ['', ''],
        ];
    }
}
