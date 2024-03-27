<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer;

use Codeception\Test\Unit;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\OnlineProfileMerchantProfileForm;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiDependencyProvider;
use Spryker\Zed\MerchantProfileMerchantPortalGuiExtension\Dependency\Plugin\OnlineProfileMerchantProfileFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfileMerchantPortalGui
 * @group Communication
 * @group Form
 * @group Transformer
 * @group OnlineProfileMerchantProfileFormTest
 * Add your own group annotations below this line
 */
class OnlineProfileMerchantProfileFormTest extends Unit
{
    /**
     * @return void
     */
    public function testOnlineProfileMerchantProfileFormTestExecutesOnlineProfileMerchantProfileFormExpanderPlugins(): void
    {
        // Assert
        $onlineProfileMerchantProfileFormExpanderPluginMock = $this->getMockBuilder(OnlineProfileMerchantProfileFormExpanderPluginInterface::class)->getMock();
        $onlineProfileMerchantProfileFormExpanderPluginMock
            ->expects($this->once())
            ->method('buildForm');

        // Arrange
        $this->tester->setDependency(MerchantProfileMerchantPortalGuiDependencyProvider::PLUGINS_ONLINE_PROFILE_MERCHANT_PROFILE_FORM_EXPANDER, [
            $onlineProfileMerchantProfileFormExpanderPluginMock,
        ]);
        $merchantProfileMerchantPortalGuiCommunicationFactoryMock = $this->createMock(MerchantProfileMerchantPortalGuiCommunicationFactory::class);
        $merchantProfileMerchantPortalGuiCommunicationFactoryMock->method('getOnlineProfileMerchantProfileFormExpanderPlugins')->willReturn([$onlineProfileMerchantProfileFormExpanderPluginMock]);
        $onlineProfileMerchantProfileFormMock = $this->getMockBuilder(OnlineProfileMerchantProfileForm::class)
            ->onlyMethods(['getFactory', 'addUrlCollectionField'])
            ->disableOriginalConstructor()
            ->getMock();
        $onlineProfileMerchantProfileFormMock->method('getFactory')->willReturn($merchantProfileMerchantPortalGuiCommunicationFactoryMock);
        $onlineProfileMerchantProfileFormMock->method('addUrlCollectionField')->willReturn($onlineProfileMerchantProfileFormMock);

        // Act
        $onlineProfileMerchantProfileFormMock->buildForm($this->createMock(FormBuilderInterface::class), []);
    }
}
