<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ServiceDateTimeEnabledProductConcreteForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement\ServiceDateTimeEnabledProductFormTransferMapperExpanderPlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ServiceDateTimeEnabledProductFormTransferMapperExpanderPluginTest
 */
class ServiceDateTimeEnabledProductFormTransferMapperExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester
     */
    protected SspServiceManagementCommunicationTester $tester;

    /**
     * @return void
     */
    public function testMapShouldMapFormDataToProductConcreteTransfer(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $formData = [
            ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED => true,
        ];

        // Act
        $productConcreteTransfer = (new ServiceDateTimeEnabledProductFormTransferMapperExpanderPlugin())
            ->map($productConcreteTransfer, $formData);

        // Assert
        $this->assertTrue($productConcreteTransfer->getIsServiceDateTimeEnabled());
    }

    /**
     * @return void
     */
    public function testMapShouldSetFalseWhenServiceDateTimeEnabledIsFalse(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $formData = [
            ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED => false,
        ];

        // Act
        $productConcreteTransfer = (new ServiceDateTimeEnabledProductFormTransferMapperExpanderPlugin())
            ->map($productConcreteTransfer, $formData);

        // Assert
        $this->assertFalse($productConcreteTransfer->getIsServiceDateTimeEnabled());
    }

    /**
     * @return void
     */
    public function testMapShouldNotMapWhenServiceDateTimeEnabledIsNotProvided(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $formData = [];

        // Act
        $productConcreteTransfer = (new ServiceDateTimeEnabledProductFormTransferMapperExpanderPlugin())
            ->map($productConcreteTransfer, $formData);

        // Assert
        $this->assertNull($productConcreteTransfer->getIsServiceDateTimeEnabled());
    }

    /**
     * @return void
     */
    public function testMapShouldOverwriteExistingValue(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIsServiceDateTimeEnabled(true);
        $formData = [
            ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED => false,
        ];

        // Act
        $productConcreteTransfer = (new ServiceDateTimeEnabledProductFormTransferMapperExpanderPlugin())
            ->map($productConcreteTransfer, $formData);

        // Assert
        $this->assertFalse($productConcreteTransfer->getIsServiceDateTimeEnabled());
    }
}
