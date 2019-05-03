<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilEncryption;

use Codeception\Test\Unit;
use Spryker\Service\UtilEncryption\UtilEncryptionServiceInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilEncryption
 * @group UtilEncryptionServiceTest
 * Add your own group annotations below this line
 */
class UtilEncryptionServiceTest extends Unit
{
    protected const ENCTYPTION_KEY = 'ENCTYPTION_KEY';
    protected const ENCTYPTION_PLAIN_TEXT = 'ENCTYPTION_PLAIN_TEXT';

    /**
     * @var \SprykerTest\Service\UtilEncryption\UtilEncryptionServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateEncryptInitVectorGeneratesSuccessfull(): void
    {
        //Act
        $initVector = $this->getUtilEncryptionService()->generateEncryptInitVector();

        //Assert
        $this->assertNotEmpty($initVector);
    }

    /**
     * @return void
     */
    public function testEncryptEncryptsStringCorrectly(): void
    {
        //Arrange
        $encryptInitVector = $this->getUtilEncryptionService()->generateEncryptInitVector();

        //Act
        $encryptedString = $this->getUtilEncryptionService()->encrypt(
            static::ENCTYPTION_PLAIN_TEXT,
            $encryptInitVector,
            static::ENCTYPTION_KEY
        );

        //Assert
        $this->assertNotEmpty($encryptedString);
        $this->assertNotEquals($encryptedString, static::ENCTYPTION_PLAIN_TEXT);
    }

    /**
     * @return void
     */
    public function testDecryptDecryptsStringCorrectly(): void
    {
        //Arrange
        $encryptInitVector = $this->getUtilEncryptionService()->generateEncryptInitVector();
        $encryptedString = $this->getUtilEncryptionService()->encrypt(
            static::ENCTYPTION_PLAIN_TEXT,
            $encryptInitVector,
            static::ENCTYPTION_KEY
        );

        //Act
        $decryptedString = $this->getUtilEncryptionService()->decrypt($encryptedString, $encryptInitVector, static::ENCTYPTION_KEY);

        //Assert
        $this->assertNotEmpty($decryptedString);
        $this->assertEquals($decryptedString, static::ENCTYPTION_PLAIN_TEXT);
    }

    /**
     * @return \Spryker\Service\UtilEncryption\UtilEncryptionServiceInterface
     */
    protected function getUtilEncryptionService(): UtilEncryptionServiceInterface
    {
        return $this->tester->getLocator()->utilEncryption()->service();
    }
}
