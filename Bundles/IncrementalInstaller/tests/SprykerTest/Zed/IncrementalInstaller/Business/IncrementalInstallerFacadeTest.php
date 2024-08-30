<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\IncrementalInstaller\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IncrementalInstallerCollectionRequestTransfer;
use Generated\Shared\Transfer\IncrementalInstallerConditionsTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;
use Generated\Shared\Transfer\IncrementalInstallerTransfer;
use Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerFacadeInterface;
use SprykerTest\Zed\IncrementalInstaller\IncrementalInstallerBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group IncrementalInstaller
 * @group Business
 * @group Facade
 * @group IncrementalInstallerFacadeTest
 * Add your own group annotations below this line
 */
class IncrementalInstallerFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\IncrementalInstaller\IncrementalInstallerBusinessTester
     */
    protected IncrementalInstallerBusinessTester $tester;

    /**
     * @var \Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerFacadeInterface
     */
    protected IncrementalInstallerFacadeInterface $incrementalInstallerFacade;

    /**
     * @var string
     */
    protected const INSTALLER_NAME = 'test-installer';

    /**
     * @var int
     */
    protected const BATCH_NUMBER = 1;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->incrementalInstallerFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testCreateIncrementalInstallerCollectionSuccessfullySavesDataToDatabase(): void
    {
        // Arrange
        $incrementalInstallerCollectionRequestTransfer = new IncrementalInstallerCollectionRequestTransfer();
        $incrementalInstallerTransfer = (new IncrementalInstallerTransfer())
            ->setInstaller(static::INSTALLER_NAME)
            ->setBatch(static::BATCH_NUMBER);
        $incrementalInstallerCollectionRequestTransfer->addIncrementalInstaller($incrementalInstallerTransfer);

        // Act
        $incrementalInstallerCollectionResponseTransfer = $this->incrementalInstallerFacade->createIncrementalInstallerCollection($incrementalInstallerCollectionRequestTransfer);
        $installerFromDatabase = $this->tester->getInstallerByName(static::INSTALLER_NAME);

        // Assert
        $this->tester->assertTrue($incrementalInstallerCollectionResponseTransfer->getIncrementalInstallers()->count() > 0);
        $this->tester->assertCount(0, $incrementalInstallerCollectionResponseTransfer->getErrors());
        $this->tester->assertEquals(static::INSTALLER_NAME, $installerFromDatabase->getInstaller());
        $this->tester->assertEquals(static::BATCH_NUMBER, $installerFromDatabase->getBatch());
    }

    /**
     * @return void
     */
    public function testGetIncrementalInstallerCollectionReturnsDataFromDatabase(): void
    {
        // Arrange
        $this->tester->haveIncrementalInstaller(static::INSTALLER_NAME, static::BATCH_NUMBER);
        $incrementalInstallerConditionsTransfer = (new IncrementalInstallerConditionsTransfer())->setBatch(static::BATCH_NUMBER);
        $incrementalInstallerCriteriaTransfer = (new IncrementalInstallerCriteriaTransfer())
            ->setIncrementalInstallerConditions($incrementalInstallerConditionsTransfer);

        // Act
        $incrementalInstallerCollectionTransfer = $this->incrementalInstallerFacade->getIncrementalInstallerCollection($incrementalInstallerCriteriaTransfer);

        // Assert
        $this->tester->assertTrue($incrementalInstallerCollectionTransfer->getIncrementalInstallers()->count() > 0);
    }
}
