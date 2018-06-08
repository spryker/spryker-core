<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SprykGui\Communication\Form\Type\NewModuleType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class SprykMainForm extends AbstractType
{
    protected const SPRYK = 'spryk';
    protected const MODULE = 'module';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver|void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::SPRYK,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $spryk = $options[static::SPRYK];
        $sprykDefinition = $this->getFacade()->getSprykDefinitionByName($spryk);

        if (isset($sprykDefinition['arguments']['module']['type'])) {
            $builder->add(static::MODULE, NewModuleType::class, ['sprykDefinition' => $sprykDefinition]);
            $builder->add('submit', SubmitType::class);

            return;
        }

        $moduleCollectionTransfer = $this->getFacade()->getModules();

        $builder->add(static::MODULE, ChoiceType::class, [
            'choices' => $moduleCollectionTransfer->getModules(),
            'choice_label' => function (ModuleTransfer $moduleTransfer) {
                return $moduleTransfer->getName();
            },
            'group_by' => function (ModuleTransfer $moduleTransfer) {
                return $moduleTransfer->getOrganization();
            },
            'data_class' => ModuleTransfer::class,
            'placeholder' => '',
        ]);

        $builder->add('submit', SubmitType::class);
    }
}
