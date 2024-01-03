<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\ShipmentsRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\ShipmentsRestApi\CompanyBusinessUnitUuidRestAddressResponseMapperPlugin;
use SprykerTest\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CompanyBusinessUnitAddressesRestApi
 * @group Plugin
 * @group ShipmentsRestApi
 * @group CompanyBusinessUnitUuidRestAddressResponseMapperPluginTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitUuidRestAddressResponseMapperPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_UUID = 'FAKE_UUID';

    /**
     * @var \SprykerTest\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiPluginTester
     */
    protected CompanyBusinessUnitAddressesRestApiPluginTester $tester;

    /**
     * @return void
     */
    public function testShouldCopyCompanyBusinessUnitAddressUuidToRestAddressTransfer(): void
    {
        // Arrange
        $addressTransfer = (new AddressTransfer())
            ->setCompanyBusinessUnitAddressUuid(static::FAKE_UUID);

        // Act
        $restAddressTransfer = (new CompanyBusinessUnitUuidRestAddressResponseMapperPlugin())->map(
            $addressTransfer,
            new RestAddressTransfer(),
        );

        // Assert
        $this->assertSame(static::FAKE_UUID, $restAddressTransfer->getIdCompanyBusinessUnitAddress());
    }

    /**
     * @return void
     */
    public function testDoNothingWhenCompanyBusinessUnitAddressUuidIsNull(): void
    {
        // Arrange
        $addressTransfer = (new AddressTransfer())
            ->setCompanyBusinessUnitAddressUuid(null);

        // Act
        $restAddressTransfer = (new CompanyBusinessUnitUuidRestAddressResponseMapperPlugin())->map(
            $addressTransfer,
            new RestAddressTransfer(),
        );

        // Assert
        $this->assertNull($restAddressTransfer->getIdCompanyBusinessUnitAddress());
    }
}
