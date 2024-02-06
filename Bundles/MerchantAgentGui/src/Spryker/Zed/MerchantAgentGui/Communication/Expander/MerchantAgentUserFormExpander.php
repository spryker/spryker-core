<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication\Expander;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class MerchantAgentUserFormExpander implements MerchantAgentUserFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_IS_MERCHANT_AGENT = 'is_merchant_agent';

    /**
     * @var string
     */
    protected const LABEL_IS_MERCHANT_AGENT = 'This user is an agent in Merchant Portal';

    /**
     * @var string
     */
    protected const TEMPLATE_PATH_IS_MERCHANT_AGENT = '@MerchantAgentGui/_partials/user-form-is-merchant-agent-field.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return void
     */
    public function expandForm(FormBuilderInterface $builder): void
    {
        $this->addIsMerchantAgentField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return void
     */
    protected function addIsMerchantAgentField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_IS_MERCHANT_AGENT, CheckboxType::class, [
            'label' => static::LABEL_IS_MERCHANT_AGENT,
            'required' => false,
            'attr' => [
                'template_path' => static::TEMPLATE_PATH_IS_MERCHANT_AGENT,
            ],
        ]);
    }
}
