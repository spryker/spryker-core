<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ChecksumGenerator;

use Codeception\Test\Unit;
use Spryker\Service\ChecksumGenerator\ChecksumGeneratorServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ChecksumGenerator
 * @group ProductConfigurationDataChecksumGeneratorTest
 * Add your own group annotations below this line
 */
class ChecksumGeneratorServiceTest extends Unit
{
    protected const ENCRYPTION_KEY = 'change123';
    protected const FAKE_ENCRYPTION_KEY = 'fake_encryption_key';
    protected const HEX_INITIALIZATION_VECTOR = '0c1ffefeebdab4a3d839d0e52590c9a2';

    /**
     * @var \SprykerTest\Service\ChecksumGenerator\ChecksumGeneratorServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateOpenSslChecksumWillGeneratesEncodedCheckSum(): void
    {
        // Arrange
        $data = [
            'fakeKey1' => 'fakeValue1',
            'fakeKey2' => 'fakeValue2',
            'fakeKey3' => 'fakeValue3',
        ];

        // Act
        $encodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $data,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        // Assert
        $this->assertSame(
            32,
            mb_strlen($encodedCheckSum),
            'Expects that string length will be equal to the predefined value (32).'
        );
    }

    /**
     * @return void
     */
    public function testGenerateOpenSslChecksumWillGeneratesEncodedCheckSumForEmptyData(): void
    {
        // Arrange
        $data = [];

        // Act
        $encodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $data,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        // Assert
        $this->assertSame(
            32,
            mb_strlen($encodedCheckSum),
            'Expects that string length will be equal to the predefined value (32).'
        );
    }

    /**
     * @return void
     */
    public function testGenerateOpenSslChecksumTryToUseAnotherEncryptionKeyWillGenerateDifferentSum(): void
    {
        // Arrange
        $data = [
            'fakeKey1' => 'fakeValue1',
            'fakeKey2' => 'fakeValue2',
        ];

        // Act
        $firstEncodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $data,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        $secondEncodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $data,
                static::FAKE_ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        // Assert
        $this->assertNotSame(
            $firstEncodedCheckSum,
            $secondEncodedCheckSum,
            'Expects different checksum will be generated.'
        );
    }

    /**
     * @return void
     */
    public function testGenerateOpenSslChecksumCompareTwoChecksumWithSameData(): void
    {
        // Arrange
        $data = [
            'fakeKey1' => 'fakeValue1',
            'fakeKey2' => 'fakeValue2',
        ];

        // Act
        $firstEncodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $data,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        $secondEncodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $data,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        // Assert
        $this->assertSame(
            $firstEncodedCheckSum,
            $secondEncodedCheckSum,
            'Expects equal checksum values when same data.'
        );
    }

    /**
     * @return void
     */
    public function testGenerateOpenSslChecksumCompareTwoChecksumWithDifferentData(): void
    {
        // Arrange
        $firstData = [
            'fakeKey1' => 'fakeValue1',
            'fakeKey2' => 'fakeValue2',
        ];

        $secondData = [
            'fakeKey1' => 'fakeValue1',
            'fakeKey2' => 'differentFakeValue2',
        ];

        // Act
        $firstEncodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $firstData,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        $secondEncodedCheckSum = $this->getCheckSumGenerator()
            ->generateOpenSslChecksum(
                $secondData,
                static::ENCRYPTION_KEY,
                static::HEX_INITIALIZATION_VECTOR
            );

        // Assert
        $this->assertNotSame(
            $firstEncodedCheckSum,
            $secondEncodedCheckSum,
            'Expects not equal checksum values with different data.'
        );
    }

    /**
     * @return \Spryker\Service\ChecksumGenerator\ChecksumGeneratorServiceInterface
     */
    protected function getCheckSumGenerator(): ChecksumGeneratorServiceInterface
    {
        return $this->tester->getLocator()->checksumGenerator()->service();
    }
}
