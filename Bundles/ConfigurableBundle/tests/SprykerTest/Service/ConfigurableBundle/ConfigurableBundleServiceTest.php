<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ConfigurableBundle;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ConfigurableBundle
 * @group ConfigurableBundleServiceTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\ConfigurableBundle\ConfigurableBundleServiceTester
     */
    protected ConfigurableBundleServiceTester $tester;

    /**
     * @return void
     */
    public function testExpandConfiguredBundleWithGroupKeyAddsGroupKeyToConfiguredBundle(): void
    {
        // Arrange
        $configuredBundleTransfer = (new ConfiguredBundleBuilder())
            ->withTemplate([
                ConfigurableBundleTemplateTransfer::UUID => 'configurable-bundle-template-uuid',
            ])
            ->build();

        // Act
        $resultConfiguredBundleTransfer = $this->tester->getService()
            ->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);

        // Assert
        $this->assertNotNull($resultConfiguredBundleTransfer->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandConfiguredBundleWithGroupKeyThrowsExceptionWhenConfigurableBundleTemplateIsNotProvided(): void
    {
        // Arrange
        $configuredBundleTransfer = (new ConfiguredBundleBuilder([
            ConfiguredBundleTransfer::TEMPLATE => null,
        ]))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "template" of transfer `%s` is null.', ConfiguredBundleTransfer::class));

        // Act
        $this->tester->getService()->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);
    }

    /**
     * @return void
     */
    public function testExpandConfiguredBundleWithGroupKeyThrowsExceptionWhenConfigurableBundleTemplateUuidIsNotProvided(): void
    {
        // Arrange
        $configuredBundleTransfer = (new ConfiguredBundleBuilder())
            ->withTemplate([ConfigurableBundleTemplateTransfer::UUID => null])
            ->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "uuid" of transfer `%s` is null.', ConfigurableBundleTemplateTransfer::class));

        // Act
        $this->tester->getService()->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);
    }
}
