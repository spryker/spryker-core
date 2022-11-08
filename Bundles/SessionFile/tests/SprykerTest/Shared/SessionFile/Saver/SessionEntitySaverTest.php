<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionFile\Saver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilder;
use Spryker\Shared\SessionFile\Hasher\BcryptHasher;
use Spryker\Shared\SessionFile\Saver\SessionEntitySaver;
use SprykerTest\Shared\SessionFile\SessionFileSharedTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionFile
 * @group Saver
 * @group SessionEntitySaverTest
 * Add your own group annotations below this line
 */
class SessionEntitySaverTest extends Unit
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
    public function testSaveShouldSaveSessionData(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = (new SessionEntityRequestTransfer())->fromArray([
            SessionEntityRequestTransfer::ENTITY_TYPE => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_ENTITY => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_SESSION => static::TEST_ID_SESSION,
        ]);

        $filePath = $this->tester->getSessionFilePath($sessionEntityRequestTransfer);
        $this->tester->clearSessionIfExists($filePath);

        $sessionEntitySaver = new SessionEntitySaver(
            new BcryptHasher(),
            new SessionEntityFileNameBuilder($this->tester->getActiveSessionFilePath()),
        );

        // Act
        $sessionEntityResponseTransfer = $sessionEntitySaver->save($sessionEntityRequestTransfer);

        // Assert
        $this->assertTrue($sessionEntityResponseTransfer->getIsSuccessfull());
        $this->assertNotEmpty(file_get_contents($filePath));
    }
}
