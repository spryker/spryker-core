<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CompaniesRestApi\Plugin\CartReorderRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CompaniesRestApi\Plugin\CartReorderRestApi\CompanyUserCompanyCartReorderRequestExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CompaniesRestApi
 * @group Plugin
 * @group CartReorderRestApi
 * @group CompanyUserCompanyCartReorderRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class CompanyUserCompanyCartReorderRequestExpanderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_COMPANY = 1;

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
    public function testExpandSetsCompanyIdWhenRestUserHasCompanyId(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())->setIdCompany(static::ID_COMPANY);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyCartReorderRequestExpanderPlugin())
            ->expand(new CartReorderRequestTransfer(), $restUserTransfer);

        // Assert
        $this->assertNotNull($updatedCartReorderRequestTransfer->getCompanyUserTransfer());
        $this->assertEquals(
            static::ID_COMPANY,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getFkCompany(),
        );
    }

    /**
     * @return void
     */
    public function testExpandSetsCompanyUserIdWhenCompanyUserIdIsNotSetAndRestUserHasCompanyUserId(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())
            ->setIdCompany(static::ID_COMPANY)
            ->setIdCompanyUser(static::ID_COMPANY_USER);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyCartReorderRequestExpanderPlugin())
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
            ->setIdCompany(static::ID_COMPANY)
            ->setIdCompanyUser(static::ID_COMPANY_USER);
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser(static::DIFFERENT_ID_COMPANY_USER);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyCartReorderRequestExpanderPlugin())
            ->expand($cartReorderRequestTransfer, $restUserTransfer);

        // Assert
        $this->assertEquals(
            static::DIFFERENT_ID_COMPANY_USER,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getIdCompanyUser(),
        );
        $this->assertEquals(
            static::ID_COMPANY,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getFkCompany(),
        );
    }

    /**
     * @return void
     */
    public function testExpandDoesNothingWhenRestUserDoesNotHaveCompanyId(): void
    {
        // Arrange
        $restUserTransfer = (new RestUserTransfer())->setIdCompanyUser(static::ID_COMPANY_USER);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyCartReorderRequestExpanderPlugin())
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
            ->setIdCompany(static::ID_COMPANY)
            ->setIdCompanyUser(static::ID_COMPANY_USER);
        $companyUserTransfer = new CompanyUserTransfer();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $updatedCartReorderRequestTransfer = (new CompanyUserCompanyCartReorderRequestExpanderPlugin())
            ->expand($cartReorderRequestTransfer, $restUserTransfer);

        // Assert
        $this->assertSame(
            $companyUserTransfer,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer(),
        );
        $this->assertEquals(
            static::ID_COMPANY,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getFkCompany(),
        );
        $this->assertEquals(
            static::ID_COMPANY_USER,
            $updatedCartReorderRequestTransfer->getCompanyUserTransfer()->getIdCompanyUser(),
        );
    }
}
