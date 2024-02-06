<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer;
use Generated\Shared\Transfer\AclUserHasGroupTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantPortal
 * @group Business
 * @group Facade
 * @group ExpandAgentDashboardMerchantUserTableDataTest
 * Add your own group annotations below this line
 */
class ExpandAgentDashboardMerchantUserTableDataTest extends Unit
{
    /**
     * @uses \Spryker\Zed\AclMerchantPortal\Business\Expander\AgentDashboardMerchantUserTableExpander::COL_KEY_ASSIST_USER
     *
     * @var string
     */
    protected const COL_KEY_ASSIST_USER = 'assistUser';

    /**
     * @uses \Spryker\Zed\AclMerchantPortal\Business\Expander\AgentDashboardMerchantUserTableExpander::RESPONSE_DATA_KEY_ID_USER
     *
     * @var string
     */
    protected const RESPONSE_DATA_KEY_ID_USER = 'idUser';

    /**
     * @var int
     */
    protected const ID_USER_1 = 1;

    /**
     * @var int
     */
    protected const ID_USER_2 = 2;

    /**
     * @var \SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester
     */
    protected AclMerchantPortalBusinessTester $tester;

    /**
     * @return void
     */
    public function testSetsNullForAllRows(): void
    {
        // Arrange
        $guiTableDataResponseTransfer = $this->createGuiTableDataResponseTransfer();

        $aclUserHasGroupCollectionTransfer = (new AclUserHasGroupCollectionTransfer())
            ->addAclUserHasGroup((new AclUserHasGroupTransfer())->setUser((new UserTransfer())->setIdUser(static::ID_USER_1)))
            ->addAclUserHasGroup((new AclUserHasGroupTransfer())->setUser((new UserTransfer())->setIdUser(static::ID_USER_2)));
        $this->tester->mockAclFacade($aclUserHasGroupCollectionTransfer);

        // Act
        $guiTableDataResponseTransfer = $this->tester
            ->getFacade()
            ->expandAgentDashboardMerchantUserTableData($guiTableDataResponseTransfer);

        // Assert
        $this->assertNull($guiTableDataResponseTransfer->getRows()->offsetGet(0)->getResponseData()[static::COL_KEY_ASSIST_USER]);
        $this->assertNull($guiTableDataResponseTransfer->getRows()->offsetGet(1)->getResponseData()[static::COL_KEY_ASSIST_USER]);
    }

    /**
     * @return void
     */
    public function testDoesNotSetNull(): void
    {
        // Arrange
        $guiTableDataResponseTransfer = $this->createGuiTableDataResponseTransfer();

        $this->tester->mockAclFacade(new AclUserHasGroupCollectionTransfer());

        // Act
        $guiTableDataResponseTransfer = $this->tester
            ->getFacade()
            ->expandAgentDashboardMerchantUserTableData($guiTableDataResponseTransfer);

        // Assert
        $this->assertTrue($guiTableDataResponseTransfer->getRows()->offsetGet(0)->getResponseData()[static::COL_KEY_ASSIST_USER]);
        $this->assertTrue($guiTableDataResponseTransfer->getRows()->offsetGet(1)->getResponseData()[static::COL_KEY_ASSIST_USER]);
    }

    /**
     * @return void
     */
    public function testSetsNullForOneRow(): void
    {
        // Arrange
        $guiTableDataResponseTransfer = $this->createGuiTableDataResponseTransfer();

        $aclUserHasGroupCollectionTransfer = (new AclUserHasGroupCollectionTransfer())
            ->addAclUserHasGroup((new AclUserHasGroupTransfer())->setUser((new UserTransfer())->setIdUser(static::ID_USER_2)));
        $this->tester->mockAclFacade($aclUserHasGroupCollectionTransfer);

        // Act
        $guiTableDataResponseTransfer = $this->tester
            ->getFacade()
            ->expandAgentDashboardMerchantUserTableData($guiTableDataResponseTransfer);

        // Assert
        $this->assertTrue($guiTableDataResponseTransfer->getRows()->offsetGet(0)->getResponseData()[static::COL_KEY_ASSIST_USER]);
        $this->assertNull($guiTableDataResponseTransfer->getRows()->offsetGet(1)->getResponseData()[static::COL_KEY_ASSIST_USER]);
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function createGuiTableDataResponseTransfer(): GuiTableDataResponseTransfer
    {
        $rowData1 = [
            static::COL_KEY_ASSIST_USER => true,
            static::RESPONSE_DATA_KEY_ID_USER => static::ID_USER_1,
        ];
        $rowData2 = [
            static::COL_KEY_ASSIST_USER => true,
            static::RESPONSE_DATA_KEY_ID_USER => static::ID_USER_2,
        ];

        return (new GuiTableDataResponseTransfer())
            ->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($rowData1))
            ->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($rowData2));
    }
}
