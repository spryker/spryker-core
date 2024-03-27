<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\MerchantProfileMerchantPortalGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProfileMerchantPortalGuiExtension\Dependency\Plugin\OnlineProfileMerchantProfileFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig getConfig()
 */
class IsOpenForRelationRequestOnlineProfileMerchantProfileFormExpanderPlugin extends AbstractPlugin implements OnlineProfileMerchantProfileFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands `OnlineProfileMerchantProfileForm` with the field that determines merchant relation request allowance.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->getFactory()->createIsOpenForRelationRequestFormType()->buildForm($builder, $options);
    }
}
