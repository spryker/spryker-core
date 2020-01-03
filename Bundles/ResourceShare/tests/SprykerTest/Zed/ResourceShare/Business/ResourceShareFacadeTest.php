<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ResourceShare\Business;

use Codeception\TestCase\Test;
use DateTime;
use Generated\Shared\DataBuilder\ResourceShareBuilder;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShare;
use Spryker\Zed\ResourceShare\ResourceShareDependencyProvider;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ResourceShare
 * @group Business
 * @group Facade
 * @group ResourceShareFacadeTest
 * Add your own group annotations below this line
 */
class ResourceShareFacadeTest extends Test
{
    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriter::GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED
     */
    protected const GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED = 'resource_share.generation.error.resource_type_is_not_defined';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriter::GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED
     */
    protected const GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED = 'resource_share.generation.error.customer_reference_is_not_defined';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareActivator::GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER
     */
    protected const GLOSSARY_KEY_STRATEGY_EXPECTS_LOGGED_IN_CUSTOMER = 'resource_share.activator.error.strategy_expects_logged_in_customer';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
     */
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.reader.error.resource_is_not_found_by_provided_uuid';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidator::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
     */
    protected const GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED = 'resource_share.validation.error.resource_share_is_expired';

    protected const VALUE_RESOURCE_SHARE_UUID = 'VALUE_RESOURCE_SHARE_UUID';
    protected const VALUE_CUSTOMER_REFERENCE = 'VALUE_CUSTOMER_REFERENCE';
    protected const VALUE_RESOURCE_TYPE = 'VALUE_RESOURCE_TYPE';

    protected const VALUE_RESOURCE_DATA = 'VALUE_RESOURCE_DATA';
    protected const VALUE_RESOURCE_DATA_REPLACED = 'VALUE_RESOURCE_DATA_REPLACED';
    protected const VALUE_RESOURCE_DATA_EXPANDED = '_EXPANDED';

    protected const KEY_RESOURCE_DATA = 'KEY_RESOURCE_DATA';

    /**
     * @var \SprykerTest\Zed\ResourceShare\ResourceShareBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldGenerateResourceWhenResourceTypeAndResourceDataAreUnique(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNotNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAddErrorMessageWhenResourceTypeIsNotDefined(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setResourceType(null);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_TYPE_IS_NOT_DEFINED
        ));
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAllowResourceGenerationWithEmptyResourceData(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setResourceShareData(null);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertEmpty($resourceShareResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAddErrorMessageWhenCustomerReferenceIsNotDefined(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setCustomerReference(null);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_CUSTOMER_REFERENCE_IS_NOT_DEFINED
        ));
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAddErrorMessageWhenResourceIsAlreadyShared(): void
    {
        // Arrange
        $resourceShareTransfer = $this->tester->haveResourceShare();
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setResourceType($resourceShareTransfer->getResourceType())
            ->setResourceShareData($resourceShareTransfer->getResourceShareData())
            ->setCustomerReference($resourceShareTransfer->getCustomerReference());

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertEmpty($resourceShareResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareWillNotGenerateExpiredResourceShare(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setExpiryDate(
            (new DateTime('Today last year'))->format('Y-m-d')
        );

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
        ));
    }

    /**
     * @return void
     */
    public function testGetResourceShareByUuidShouldAddErrorMessageWhenResourceIsNotFound(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setUuid(static::VALUE_RESOURCE_SHARE_UUID);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->getResourceShareByUuid(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
        ));
    }

    /**
     * @return void
     */
    public function testGetResourceShareByUuidShouldReturnCorrectResourceShareFromDatabase(): void
    {
        // Arrange
        $resourceShareTransfer = $this->tester->haveResourceShare();

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->getResourceShareByUuid(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $resourceShareTransfer->getIdResourceShare(),
            $resourceShareResponseTransfer->getResourceShare()->getIdResourceShare()
        );
    }

    /**
     * @return void
     */
    public function testGetResourceShareByUuidWillNotRetrieveExpiredResourceShare(): void
    {
        // Arrange
        $resourceShareTransfer = $this->createExpiredResourceShare();

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->getResourceShareByUuid(
            (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
        );

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
        ));
    }

    /**
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    protected function createExpiredResourceShare(): ResourceShareTransfer
    {
        $resourceShareTransfer = (new ResourceShareBuilder([
            ResourceShareTransfer::EXPIRY_DATE => (new DateTime('Today last year'))->format('Y-m-d'),
        ]))->build();

        $resourceShareEntity = new SpyResourceShare();
        $resourceShareEntity->fromArray($resourceShareTransfer->toArray());

        $resourceShareEntity->save();

        return (new ResourceShareTransfer())->fromArray($resourceShareEntity->toArray(), true);
    }

    /**
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface $resourceShareActivatorStrategyPlugin
     *
     * @return void
     */
    protected function registerResourceShareActivatorStrategyPlugin(
        ResourceShareZedActivatorStrategyPluginInterface $resourceShareActivatorStrategyPlugin
    ): void {
        $this->tester->setDependency(ResourceShareDependencyProvider::PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY, [
            $resourceShareActivatorStrategyPlugin,
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
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResourceShareActivatorStrategyPluginMock()
    {
        return $this->createMock(ResourceShareZedActivatorStrategyPluginInterface::class);
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
