<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentGui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class UserAgentFormExpanderPlugin extends AbstractPlugin implements UserFormExpanderPluginInterface
{
    protected const FIELD_IS_AGENT = 'is_agent';
    protected const FIELD_IS_AGENT_LABEL = 'Yes, this user is an agent';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $this->addIsAgentField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIsAgentField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_IS_AGENT, CheckboxType::class, [
            'label' => static::FIELD_IS_AGENT_LABEL,
            'required' => false,
        ]);
    }
}
