<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionFile\Handler;

use Codeception\Test\Unit;
use Spryker\Shared\SessionFile\Handler\AbstractSessionAccountHandlerFile;
use Spryker\Shared\SessionFile\Hasher\BcryptHasher;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionFile
 * @group Handler
 * @group AbstractSessionAccountHandlerFileTest
 * Add your own group annotations below this line
 */
class AbstractSessionAccountHandlerFileTest extends Unit
{
    /**
     * @var string
     */
    public const ACTIVE_SESSION_FILE_PATH = __DIR__ . '/../../../_output';

    /**
     * @var string
     */
    public const ACCOUNT_TYPE = 'account_type';

    /**
     * @return void
     */
    public function testSaveCustomerSessionSuccessful(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $hashedSessionId = 'hashedSessionId';

        $this->clearSessionIfExists($idAccount);

        $hasher = $this->getMockBuilder(HasherInterface::class)->getMock();
        $hasher->expects(static::once())->method('encrypt')->with($idSession)->willReturn($hashedSessionId);

        // Action
        $this->createSessionAccountHandler(
            $hasher,
        )->saveSessionAccount($idAccount, $idSession);

        // Assert
        $this->assertSame(
            $hashedSessionId,
            file_get_contents($this->getFilePath($idAccount)),
        );

        $this->clearSessionIfExists($idAccount);
    }

    /**
     * @return void
     */
    public function testIsSessionAccountValidReturnsTrueWhenIdAccountNotFound(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $this->clearSessionIfExists($idAccount);

        // Action
        $result = $this->createSessionAccountHandler(
            new BcryptHasher(),
        )->isSessionAccountValid($idAccount, $idSession);

        // Assert
        $this->assertTrue($result, 'Session id must be valid.');
        $this->clearSessionIfExists($idAccount);
    }

    /**
     * @return void
     */
    public function testIsSessionAccountValidReturnsFalseWhenSessionIdIsInvalid(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        file_put_contents($this->getFilePath($idAccount), 'invalid_hash');

        // Action
        $result = $this->createSessionAccountHandler(
            new BcryptHasher(),
        )->isSessionAccountValid($idAccount, $idSession);

        // Assert
        $this->assertFalse($result, 'Session id must be invalid.');

        $this->clearSessionIfExists($idAccount);
    }

    /**
     * @return void
     */
    public function testIsSessionAccountValidReturnsTrueWhenSessionIdIsValid(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $hashedSessionId = 'hashedSessionId';
        $hasher = $this->getMockBuilder(HasherInterface::class)->getMock();
        $hasher->expects(static::once())->method('validate')->with($idSession, $hashedSessionId)->willReturn(true);
        file_put_contents($this->getFilePath($idAccount), $hashedSessionId);

        // Action
        $result = $this->createSessionAccountHandler(
            $hasher,
        )->isSessionAccountValid($idAccount, $idSession);

        // Assert
        $this->assertTrue($result, 'Session id must be valid.');

        $this->clearSessionIfExists($idAccount);
    }

    /**
     * @param \Spryker\Shared\SessionFile\Hasher\HasherInterface $hasher
     *
     * @return \Spryker\Shared\SessionFile\Handler\AbstractSessionAccountHandlerFile
     */
    protected function createSessionAccountHandler(
        HasherInterface $hasher
    ): AbstractSessionAccountHandlerFile {
        return new class ($hasher) extends AbstractSessionAccountHandlerFile {
            /**
             * @return string
             */
            protected function getAccountType(): string
            {
                return AbstractSessionAccountHandlerFileTest::ACCOUNT_TYPE;
            }

            /**
             * @return string
             */
            protected function getActiveSessionFilePath(): string
            {
                return AbstractSessionAccountHandlerFileTest::ACTIVE_SESSION_FILE_PATH;
            }
        };
    }

    /**
     * @param int $accountId
     *
     * @return void
     */
    protected function clearSessionIfExists(int $accountId): void
    {
        $filePath = $this->getFilePath($accountId);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * @param int $accountId
     *
     * @return string
     */
    protected function getFilePath(int $accountId): string
    {
        return static::ACTIVE_SESSION_FILE_PATH .
            sprintf(
                '/session:%s:%s',
                static::ACCOUNT_TYPE,
                $accountId,
            );
    }
}
