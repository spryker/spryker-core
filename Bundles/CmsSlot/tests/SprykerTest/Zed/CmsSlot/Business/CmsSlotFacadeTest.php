<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlot\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotTemplateException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlot
 * @group Business
 * @group Facade
 * @group CmsSlotFacadeTest
 * Add your own group annotations below this line
 */
class CmsSlotFacadeTest extends Unit
{
    protected const EXCEPTION_ERROR_MESSAGE_MISSING_CMS_SLOT_TEMPLATE = 'CMS Slot Template with id "%d" not found.';

    /**
     * @var \SprykerTest\Zed\CmsSlot\CmsSlotBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateCmsSlotSuccess(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlot();
        $validationResponse = $this->tester->getFacade()->validateCmsSlot($cmsSlotTransfer);

        $this->assertTrue($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotFailsOnInvalidName(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlot([
            CmsSlotTransfer::NAME => str_repeat('t', 300),
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlot($cmsSlotTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotFailsOnInvalidDescription(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlot([
            CmsSlotTransfer::DESCRIPTION => str_repeat('t', 1500),
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlot($cmsSlotTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotFailsOnInvalidKey(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlot([
            CmsSlotTransfer::KEY => 'invalid key',
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlot($cmsSlotTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotFailsOnInvalidContentProviderType(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlot([
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => '',
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlot($cmsSlotTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotFailsOnInvalidIsActive(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlot([
            CmsSlotTransfer::IS_ACTIVE => 2,
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlot($cmsSlotTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotTemplateSuccess(): void
    {
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplate();
        $validationResponse = $this->tester->getFacade()->validateCmsSlotTemplate($cmsSlotTemplateTransfer);

        $this->assertTrue($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotTemplateFailsOnInvalidName(): void
    {
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplate([
            CmsSlotTemplateTransfer::NAME => str_repeat('t', 300),
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlotTemplate($cmsSlotTemplateTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotTemplateFailsOnInvalidDescription(): void
    {
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplate([
            CmsSlotTemplateTransfer::DESCRIPTION => str_repeat('t', 1500),
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlotTemplate($cmsSlotTemplateTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCmsSlotTemplateFailsOnInvalidPath(): void
    {
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplate([
            CmsSlotTemplateTransfer::PATH => 'invalid path',
        ]);
        $validationResponse = $this->tester->getFacade()->validateCmsSlotTemplate($cmsSlotTemplateTransfer);

        $this->assertFalse($validationResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testActivateByIdCmsSlotSuccess(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::IS_ACTIVE => false,
        ]);

        $this->tester->getFacade()->activateByIdCmsSlot($cmsSlotTransfer->getIdCmsSlot());

        $this->assertTrue($this->tester->isActiveCmsSlotById($cmsSlotTransfer->getIdCmsSlot()));
    }

    /**
     * @return void
     */
    public function testDeactivateByIdCmsSlotSuccess(): void
    {
        $cmsSlotTransfer = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->getFacade()->deactivateByIdCmsSlot($cmsSlotTransfer->getIdCmsSlot());

        $this->assertFalse($this->tester->isActiveCmsSlotById($cmsSlotTransfer->getIdCmsSlot()));
    }

    /**
     * @return void
     */
    public function testGetCmsSlotTemplateByIdIsSuccessful(): void
    {
        //Arrange
        $cmsSlotTemplateTransfer = $this->tester->haveCmsSlotTemplateInDb();

        //Act
        $foundCmsSlotTemplateTransfer = $this->tester->getFacade()->getCmsSlotTemplateById(
            $cmsSlotTemplateTransfer->getIdCmsSlotTemplate()
        );

        // Assert
        $this->assertInstanceOf(CmsSlotTemplateTransfer::class, $cmsSlotTemplateTransfer);
        $this->assertInstanceOf(CmsSlotTemplateTransfer::class, $foundCmsSlotTemplateTransfer);
        $this->assertEquals(
            $cmsSlotTemplateTransfer->getIdCmsSlotTemplate(),
            $foundCmsSlotTemplateTransfer->getIdCmsSlotTemplate()
        );
    }

    /**
     * @return void
     */
    public function testGetCmsSlotTemplateByIdIsFailed(): void
    {
        //Arrange
        $this->tester->ensureCmsSlotTemplateTableIsEmpty();
        $this->expectExceptionObject(
            new MissingCmsSlotTemplateException(
                sprintf(
                    static::EXCEPTION_ERROR_MESSAGE_MISSING_CMS_SLOT_TEMPLATE,
                    1
                )
            )
        );

        //Act
        $cmsSlotTemplateTransfer = $this->tester->getFacade()->getCmsSlotTemplateById(1);

        // Assert
        $this->assertNull($cmsSlotTemplateTransfer);
    }
}
