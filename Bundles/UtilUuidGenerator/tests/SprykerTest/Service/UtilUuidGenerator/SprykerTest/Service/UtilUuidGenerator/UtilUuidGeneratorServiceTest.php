<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilUuidGenerator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IdGeneratorSettingsTransfer;
use Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorService;
use Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilUuidGenerator
 * @group UtilUuidGeneratorServiceTest
 * Add your own group annotations below this line
 */
class UtilUuidGeneratorServiceTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_VALUE_ENCODED = 'carts.DE--1.Shopping Cart';

    /**
     * @var string
     */
    protected const TEST_VALUE_DECODED = '9673c873-e007-5930-a37b-5cedf2c2f10f';

    /**
     * @var \SprykerTest\Service\UtilUuidGenerator\UtilUuidGeneratorTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateUuid5ShouldReturnCorrectUuid(): void
    {
        $service = $this->getUtilUuidGeneratorService();

        $generatedValue = $service->generateUuid5FromObjectId(static::TEST_VALUE_ENCODED);

        $this->tester->assertSame(static::TEST_VALUE_DECODED, $generatedValue);
    }

    /**
     * @return void
     */
    public function testGenerateUuid5ShouldReturnSameUuidForSameResourceName(): void
    {
        $service = $this->getUtilUuidGeneratorService();

        $firstGeneratedValue = $service->generateUuid5FromObjectId(static::TEST_VALUE_ENCODED);
        $secondGeneratedValue = $service->generateUuid5FromObjectId(static::TEST_VALUE_ENCODED);

        $this->tester->assertSame($firstGeneratedValue, $secondGeneratedValue);
    }

    /**
     * @return void
     */
    public function testGenerateUniqueRandomIdUnique(): void
    {
        // Arrange
        $service = $this->getUtilUuidGeneratorService();
        $idGeneratorSettingsTransfer = (new IdGeneratorSettingsTransfer())
            ->setAlphabet('1234567890')
            ->setSize(15);

        // Act
        $generatedValues = [];
        for ($i = 0; $i < 10000; $i++) {
            $generatedValues[] = $service->generateUniqueRandomId($idGeneratorSettingsTransfer);
        }

        // Assert
        $this->tester->assertSame(count($generatedValues), count(array_unique($generatedValues)));
    }

    /**
     * @return void
     */
    public function testGenerateUniqueRandomIdSplit(): void
    {
        // Arrange
        $service = $this->getUtilUuidGeneratorService();
        $idGeneratorSettingsTransfer = (new IdGeneratorSettingsTransfer())
            ->setAlphabet('1234567890')
            ->setSize(15)
            ->setSplitLength(5)
            ->setSplitSeparator('-');

        // Act
        $uniqueRandomId = $service->generateUniqueRandomId($idGeneratorSettingsTransfer);

        // Assert
        $this->tester->assertRegExp('/^[0-9]{5}-[0-9]{5}-[0-9]{5}$/', $uniqueRandomId);
    }

    /**
     * @return \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface
     */
    protected function getUtilUuidGeneratorService(): UtilUuidGeneratorServiceInterface
    {
        return new UtilUuidGeneratorService();
    }
}
