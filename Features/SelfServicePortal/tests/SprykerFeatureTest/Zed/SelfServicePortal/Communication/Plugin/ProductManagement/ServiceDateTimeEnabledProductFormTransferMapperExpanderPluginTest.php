<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ServiceDateTimeEnabledProductFormTransferMapperExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ServiceDateTimeEnabledProductConcreteForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ServiceDateTimeEnabledProductFormTransferMapperExpanderPluginTest
 */
class ServiceDateTimeEnabledProductFormTransferMapperExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

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
