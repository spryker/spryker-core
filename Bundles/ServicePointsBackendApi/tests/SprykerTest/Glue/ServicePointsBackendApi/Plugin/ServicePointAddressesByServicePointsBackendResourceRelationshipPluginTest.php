<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer;
use Generated\Shared\Transfer\ApiServicePointsAttributesTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeBridge;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ServicePointAddressesByServicePointsBackendResourceRelationshipPlugin;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiDependencyProvider;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Plugin
 * @group ServicePointAddressesByServicePointsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ServicePointAddressesByServicePointsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID = 'service-point-uuid';

    /**
     * @var string
     */
    protected const SERVICE_POINT_ADDRESS_UUID = 'service-point-address-uuid';

    /**
     * @var string
     */
    protected const GLUE_RESOURCE_NOT_EXISTING = 'not-existing-glue-resource';

    /**
     * @var string
     */
    protected const COUNTRY_ISO2_CODE = '01';

    /**
     * @var \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester
     */
    protected ServicePointsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddServicePointAddressesRelationshipToGlueResourceTransfer(): void
    {
        // Arrange
        $this->mockServicePointFacade([
            (new ServicePointAddressTransfer())
                ->setUuid(static::SERVICE_POINT_ADDRESS_UUID)
                ->setCountry((new CountryTransfer())->setIso2Code(static::COUNTRY_ISO2_CODE))
                ->setServicePoint((new ServicePointTransfer())->setUuid(static::SERVICE_POINT_UUID)),
        ]);
        $glueRequestTransfer = new GlueRequestTransfer();

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_POINT_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
                ->setAttributes(new ApiServicePointsAttributesTransfer()),
        ];

        // Act
        (new ServicePointAddressesByServicePointsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfer->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfer->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(
            ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINT_ADDRESSES,
            $glueResourceTransfer->getType(),
        );
        $this->assertInstanceOf(ApiServicePointAddressesAttributesTransfer::class, $glueResourceTransfer->getAttributes());

        $this->assertSame(static::SERVICE_POINT_ADDRESS_UUID, $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddServicePointAddressesRelationshipToGlueResourceTransferWhenServicePointHasNoAddress(): void
    {
        // Arrange
        $this->mockServicePointFacade([]);
        $glueRequestTransfer = new GlueRequestTransfer();

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_POINT_UUID)
                ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
                ->setAttributes(new ApiServicePointsAttributesTransfer()),
        ];

        // Act
        (new ServicePointAddressesByServicePointsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfer->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddServicePointAddressesRelationshipToGlueResourceTransferWhenGlueResourceIsWrong(): void
    {
        // Arrange
        $this->mockServicePointFacade([
            (new ServicePointAddressTransfer())
                ->setUuid(static::SERVICE_POINT_ADDRESS_UUID)
                ->setCountry((new CountryTransfer())->setIso2Code(static::COUNTRY_ISO2_CODE))
                ->setServicePoint((new ServicePointTransfer())->setUuid(static::SERVICE_POINT_UUID)),
        ]);
        $glueRequestTransfer = new GlueRequestTransfer();

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId(static::SERVICE_POINT_UUID)
                ->setType(static::GLUE_RESOURCE_NOT_EXISTING)
                ->setAttributes(new ApiServicePointsAttributesTransfer()),
        ];

        // Act
        (new ServicePointAddressesByServicePointsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $glueResourceTransfer = reset($glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfer->getRelationships());
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return void
     */
    protected function mockServicePointFacade(array $servicePointAddressTransfers): void
    {
        $servicePointFacade = $this->getMockBuilder(ServicePointsBackendApiToServicePointFacadeBridge::class)
            ->onlyMethods(['getServicePointAddressCollection'])
            ->disableOriginalConstructor()
            ->getMock();

        $servicePointFacade->method('getServicePointAddressCollection')->willReturn(
            (new ServicePointAddressCollectionTransfer())->setServicePointAddresses(new ArrayObject($servicePointAddressTransfers)),
        );

        $this->tester->setDependency(ServicePointsBackendApiDependencyProvider::FACADE_SERVICE_POINT, $servicePointFacade);
    }
}
