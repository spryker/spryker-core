<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CompanyBusinessUnitsRestApi\Plugin\CartReorderRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Plugin\CartReorderRestApi\CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CompanyBusinessUnitsRestApi
 * @group Plugin
 * @group CartReorderRestApi
 * @group CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_COMPANY_BUSINESS_UNIT = 1;

    /**
     * @var int
     */
    protected const ID_COMPANY_USER = 2;

    /**
     * @var int
     */
    protected const DIFFERENT_ID_COMPANY_USER = 3;

    /**
     * @return void
     */
    public function testExpandSetsCompanyBusinessUnitIdWhenRestUserHasCompanyBusinessUnitId(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin())
            ->expand(new CartReorderRequestTransfer(), $restUserTransfer);

        // Assert
        $this->assertNotNull($updatedCartReorderRequestTransfer->getCompanyUserTransfer());
        $this->assertEquals(
            static::ID_COMPANY_BUSINESS_UNIT,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getFkCompanyBusinessUnit(),
        );
    }

    /**
     * @return void
     */
    public function testExpandSetsCompanyUserIdWhenCompanyUserIdIsNotSetAndRestUserHasCompanyUserId(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT)
            ->setIdCompanyUser(static::ID_COMPANY_USER);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin())
            ->expand(new CartReorderRequestTransfer(), $restUserTransfer);

        // Assert
        $this->assertNotNull($updatedCartReorderRequestTransfer->getCompanyUserTransfer());
        $this->assertEquals(
            static::ID_COMPANY_USER,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getIdCompanyUser(),
        );
    }

    /**
     * @return void
     */
    public function testExpandDoesNotSetCompanyUserIdWhenCompanyUserIdIsAlreadySet(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT)
            ->setIdCompanyUser(static::ID_COMPANY_USER);
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser(static::DIFFERENT_ID_COMPANY_USER);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin())
            ->expand($cartReorderRequestTransfer, $restUserTransfer);

        // Assert
        $this->assertEquals(
            static::DIFFERENT_ID_COMPANY_USER,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getIdCompanyUser(),
        );
        $this->assertEquals(
            static::ID_COMPANY_BUSINESS_UNIT,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getFkCompanyBusinessUnit(),
        );
    }

    /**
     * @return void
     */
    public function testExpandDoesNothingWhenRestUserDoesNotHaveCompanyBusinessUnitId(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())->setIdCompanyUser(static::ID_COMPANY_USER);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin())
            ->expand(new CartReorderRequestTransfer(), $restUserTransfer);

        // Assert
        $this->assertNull($updatedCartReorderRequestTransfer->getCompanyUserTransfer());
    }

    /**
     * @return void
     */
    public function testExpandUpdatesExistingCompanyUserTransferWhenProvided(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())
            ->setIdCompanyBusinessUnit(static::ID_COMPANY_BUSINESS_UNIT)
            ->setIdCompanyUser(static::ID_COMPANY_USER);
        $companyUserTransfer = new CompanyUserTransfer();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyBusinessUnitCartReorderRequestExpanderPlugin())
            ->expand($cartReorderRequestTransfer, $restUserTransfer);

        // Assert
        $this->assertSame(
            $companyUserTransfer,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer(),
        );
        $this->assertEquals(
            static::ID_COMPANY_BUSINESS_UNIT,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getFkCompanyBusinessUnit(),
        );
        $this->assertEquals(
            static::ID_COMPANY_USER,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getIdCompanyUser(),
        );
    }
}
