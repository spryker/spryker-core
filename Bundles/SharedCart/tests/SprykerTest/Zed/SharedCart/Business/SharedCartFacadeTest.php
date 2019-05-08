<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group Facade
 * @group SharedCartFacadeTest
 * Add your own group annotations below this line
 */
class SharedCartFacadeTest extends Test
{
    /**
     * @uses \Spryker\Zed\SharedCart\Business\ResourceShare\ResourceShareDataExpanderStrategy::GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING
     */
    protected const GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING = 'shared_cart.resource_share.strategy.error.properties_are_missing';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::KEY_SHARE_OPTION
     */
    protected const KEY_SHARE_OPTION = 'share_option';

    protected const KEY_ID_QUOTE = 'id_quote';
    protected const KEY_ID_COMPANY_USER = 'id_company_user';
    protected const KEY_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';

    protected const VALUE_SHARE_OPTION = 'VALUE_SHARE_OPTION';
    protected const VALUE_ID_QUOTE = 1;
    protected const VALUE_ID_COMPANY_USER = 1;
    protected const VALUE_ID_COMPANY_BUSINESS_UNIT = 1;

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testApplyActivatorStrategyPluginShouldXXX(): void
    {
        // Arrange

        // Act

        // Assert
    }

    /**
     * @return void
     */
    public function testApplyDataExpanderStrategyPluginShouldXXX(): void
    {
        // Arrange

        // Act

        // Assert
    }

    /**
     * @return void
     */
    public function testApplyDataExpanderStrategyPluginShouldExpandResourceShareDataWithAllDataSharedCartActivatorStrategyRequires(): void
    {
        // Arrange
        $resourceShareTransfer = $this->tester->haveResourceShare([
            ResourceShareTransfer::RESOURCE_SHARE_DATA => (new ResourceShareDataTransfer())->setData([
                static::KEY_SHARE_OPTION => static::VALUE_SHARE_OPTION,
                static::KEY_ID_QUOTE => static::VALUE_ID_QUOTE,
                static::KEY_ID_COMPANY_USER => static::VALUE_ID_COMPANY_USER,
                static::KEY_ID_COMPANY_BUSINESS_UNIT => static::VALUE_ID_COMPANY_BUSINESS_UNIT,
            ]),
        ]);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyResourceShareDataExpanderStrategy($resourceShareTransfer);
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertEquals($resourceShareDataTransfer->getIdQuote(), static::VALUE_ID_QUOTE);
        $this->assertEquals($resourceShareDataTransfer->getIdCompanyUser(), static::VALUE_ID_COMPANY_USER);
        $this->assertEquals($resourceShareDataTransfer->getIdCompanyBusinessUnit(), static::VALUE_ID_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return void
     */
    public function testApplyDataExpanderStrategyPluginShouldNotExpandResourceShareDataButReturnErrorMessageIfAnyRequiredPropertyIsMissing(): void
    {
        // Arrange
        $resourceShareTransfer = $this->tester->haveResourceShare([
            ResourceShareTransfer::RESOURCE_SHARE_DATA => (new ResourceShareDataTransfer())->setData([
                static::KEY_SHARE_OPTION => null,
                static::KEY_ID_QUOTE => static::VALUE_ID_QUOTE,
                static::KEY_ID_COMPANY_USER => static::VALUE_ID_COMPANY_USER,
                static::KEY_ID_COMPANY_BUSINESS_UNIT => static::VALUE_ID_COMPANY_BUSINESS_UNIT,
            ]),
        ]);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyResourceShareDataExpanderStrategy($resourceShareTransfer);
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNull($resourceShareDataTransfer->getIdQuote());
        $this->assertNull($resourceShareDataTransfer->getIdCompanyUser());
        $this->assertNull($resourceShareDataTransfer->getIdCompanyBusinessUnit());
        $this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING
        );
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(array $seed = []): CompanyUserTransfer
    {
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        return $this->tester->haveCompanyUser($seed + [
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     * @param string $errorMessage
     *
     * @return bool
     */
    protected function hasResourceShareResponseTransferErrorMessage(
        ResourceShareResponseTransfer $resourceShareResponseTransfer,
        string $errorMessage
    ): bool {
        $resourceShareResponseTransfer->requireMessages();
        foreach ($resourceShareResponseTransfer->getMessages() as $messageTransfer) {
            $messageTransfer->requireValue();

            if ($messageTransfer->getValue() === $errorMessage) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
