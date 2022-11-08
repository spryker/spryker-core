<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionFile\Validator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilder;
use Spryker\Shared\SessionFile\Hasher\BcryptHasher;
use Spryker\Shared\SessionFile\Saver\SessionEntitySaver;
use Spryker\Shared\SessionFile\Validator\SessionEntityValidator;
use SprykerTest\Shared\SessionFile\SessionFileSharedTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionFile
 * @group Validator
 * @group SessionEntityValidatorTest
 * Add your own group annotations below this line
 */
class SessionEntityValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ENTITY_TYPE = 'test';

    /**
     * @var int
     */
    protected const TEST_ID_ENTITY = 1;

    /**
     * @var string
     */
    protected const TEST_ID_SESSION = 'testSession';

    /**
     * @var \SprykerTest\Shared\SessionFile\SessionFileSharedTester
     */
    protected SessionFileSharedTester $tester;

    /**
     * @return void
     */
    public function testValidateShouldReturnTrueWhenSessionIdIsNotSaved(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = (new SessionEntityRequestTransfer())->fromArray([
            SessionEntityRequestTransfer::ENTITY_TYPE => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_ENTITY => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_SESSION => static::TEST_ID_SESSION,
        ]);

        $this->tester->clearSessionIfExists($this->tester->getSessionFilePath($sessionEntityRequestTransfer));

        $sessionEntityValidator = new SessionEntityValidator(
            new BcryptHasher(),
            new SessionEntityFileNameBuilder($this->tester->getActiveSessionFilePath()),
        );

        // Act
        $sessionEntityResponseTransfer = $sessionEntityValidator->validate($sessionEntityRequestTransfer);

        // Assert
        $this->assertTrue($sessionEntityResponseTransfer->getIsSuccessfull());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnFalseWhenSessionIdIsInvalid(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = (new SessionEntityRequestTransfer())->fromArray([
            SessionEntityRequestTransfer::ENTITY_TYPE => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_ENTITY => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_SESSION => static::TEST_ID_SESSION,
        ]);

        $filePath = $this->tester->getSessionFilePath($sessionEntityRequestTransfer);
        file_put_contents($filePath, 'invalidSessionId');

        $sessionEntityValidator = new SessionEntityValidator(
            new BcryptHasher(),
            new SessionEntityFileNameBuilder($this->tester->getActiveSessionFilePath()),
        );

        // Act
        $sessionEntityResponseTransfer = $sessionEntityValidator->validate($sessionEntityRequestTransfer);

        // Assert
        $this->assertFalse($sessionEntityResponseTransfer->getIsSuccessfull());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnTrueWhenSessionIdIsValid(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = (new SessionEntityRequestTransfer())->fromArray([
            SessionEntityRequestTransfer::ENTITY_TYPE => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_ENTITY => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_SESSION => static::TEST_ID_SESSION,
        ]);

        $this->tester->clearSessionIfExists($this->tester->getSessionFilePath($sessionEntityRequestTransfer));

        $sessionEntityFileNameBuilder = new SessionEntityFileNameBuilder($this->tester->getActiveSessionFilePath());
        $sessionEntityValidator = new SessionEntityValidator(
            new BcryptHasher(),
            $sessionEntityFileNameBuilder,
        );

        (new SessionEntitySaver(
            new BcryptHasher(),
            $sessionEntityFileNameBuilder,
        ))->save($sessionEntityRequestTransfer);

        // Act
        $sessionEntityResponseTransfer = $sessionEntityValidator->validate($sessionEntityRequestTransfer);

        // Assert
        $this->assertTrue($sessionEntityResponseTransfer->getIsSuccessfull());
    }
}
