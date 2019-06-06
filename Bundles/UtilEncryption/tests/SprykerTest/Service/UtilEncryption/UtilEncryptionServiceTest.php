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
    protected const OPEN_SSL_ENCRYPTION_KEY = 'OPEN_SSL_ENCRYPTION_KEY';
    protected const OPEN_SSL_ENCRYPTION_PLAIN_TEXT = 'OPEN_SSL_ENCRYPTION_PLAIN_TEXT';

    /**
     * @var \SprykerTest\Service\UtilEncryption\UtilEncryptionServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateOpenSslEncryptInitVectorSuccessfull(): void
    {
        //Act
        $initVector = $this->getUtilEncryptionService()->generateOpenSslEncryptInitVector();

        //Assert
        $this->assertNotEmpty($initVector);
    }

    /**
     * @return void
     */
    public function testOpenSslEncryptEncryptsStringCorrectly(): void
    {
        //Arrange
        $encryptInitVector = $this->getUtilEncryptionService()->generateOpenSslEncryptInitVector();

        //Act
        $encryptedString = $this->getUtilEncryptionService()->encryptOpenSsl(
            static::OPEN_SSL_ENCRYPTION_PLAIN_TEXT,
            $encryptInitVector,
            static::OPEN_SSL_ENCRYPTION_KEY
        );

        //Assert
        $this->assertNotEmpty($encryptedString);
        $this->assertNotEquals($encryptedString, static::OPEN_SSL_ENCRYPTION_PLAIN_TEXT);
    }

    /**
     * @return void
     */
    public function testOpenSslDecryptDecryptsStringCorrectly(): void
    {
        //Arrange
        $encryptInitVector = $this->getUtilEncryptionService()->generateOpenSslEncryptInitVector();
        $encryptedString = $this->getUtilEncryptionService()->encryptOpenSsl(
            static::OPEN_SSL_ENCRYPTION_PLAIN_TEXT,
            $encryptInitVector,
            static::OPEN_SSL_ENCRYPTION_KEY
        );

        //Act
        $decryptedString = $this->getUtilEncryptionService()->decryptOpenSsl($encryptedString, $encryptInitVector, static::OPEN_SSL_ENCRYPTION_KEY);

        //Assert
        $this->assertNotEmpty($decryptedString);
        $this->assertEquals($decryptedString, static::OPEN_SSL_ENCRYPTION_PLAIN_TEXT);
    }

    /**
     * @return \Spryker\Service\UtilEncryption\UtilEncryptionServiceInterface
     */
    protected function getUtilEncryptionService(): UtilEncryptionServiceInterface
    {
        return $this->tester->getLocator()->utilEncryption()->service();
    }
}
