<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ServiceDateTimeEnabledProductConcreteForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement\ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPluginTest
 */
class ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester
     */
    protected SspServiceManagementCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldMapTrueValueToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIsServiceDateTimeEnabled(true);
        $formData = [];

        // Act
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin = new ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin();
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED, $formData);
        $this->assertTrue($formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED]);
    }

    /**
     * @return void
     */
    public function testExpandShouldMapFalseValueToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIsServiceDateTimeEnabled(false);
        $formData = [];

        // Act
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin = new ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin();
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED, $formData);
        $this->assertFalse($formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED]);
    }

    /**
     * @return void
     */
    public function testExpandShouldMapNullValueToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $formData = [];

        // Act
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin = new ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin();
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED, $formData);
        $this->assertNull($formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED]);
    }

    /**
     * @return void
     */
    public function testExpandShouldPreserveExistingFormData(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIsServiceDateTimeEnabled(true);
        $formData = ['existingKey' => 'existingValue'];

        // Act
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin = new ServiceDateTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin();
        $serviceDataTimeEnabledProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey('existingKey', $formData);
        $this->assertSame('existingValue', $formData['existingKey']);
        $this->assertArrayHasKey(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED, $formData);
        $this->assertTrue($formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED]);
    }
}
