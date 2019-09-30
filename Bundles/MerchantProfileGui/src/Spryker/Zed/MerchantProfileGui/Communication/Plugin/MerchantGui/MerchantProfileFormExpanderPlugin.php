<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface;
use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileFormExpanderPlugin extends AbstractPlugin implements MerchantFormExpanderPluginInterface
{
    public const FIELD_MERCHANT_PROFILE = 'merchantProfile';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addMerchantProfileFieldSubform($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantProfileFieldSubform(FormBuilderInterface $builder)
    {
        $options = $this->getMerchantProfileFormOptions($builder);
        $builder->add(
            static::FIELD_MERCHANT_PROFILE,
            MerchantProfileFormType::class,
            $options
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return array
     */
    protected function getMerchantProfileFormOptions(FormBuilderInterface $builder): array
    {
        $merchantProfileDataProvider = $this->getFactory()
            ->createMerchantProfileFormDataProvider();

        $options = $merchantProfileDataProvider->getOptions();
        /** @var \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer */
        $merchantTransfer = $builder->getForm()->getData();
        $options['data'] = $merchantProfileDataProvider->getData($merchantTransfer->getMerchantProfile());

        return $options;
    }
}
