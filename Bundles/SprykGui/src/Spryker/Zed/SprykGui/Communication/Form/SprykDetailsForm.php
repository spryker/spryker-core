<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Spryk\SprykFacade;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class SprykDetailsForm extends AbstractType
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
            static::MODULE,
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
//        $sprykDefinition = $this->getFacade()->getSprykDefinitionByName($options[static::SPRYK]);

//        if (isset($sprykDefinition['arguments']['module']['type'])) {
//            $builder->add(static::MODULE, NewModuleType::class, ['sprykDefinition' => $sprykDefinition]);
//
//            $this->addRunSprykButton($builder);
//            $this->addCreateTemplateButton($builder);
//
//            return;
//        }

        $this->addSprykDetails($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addSprykDetails(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $sprykDefinition = $this->getSprykDefinition($options[static::SPRYK]);

        $this->addArgumentsToForm($builder, $sprykDefinition, $options);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $sprykDefinition
     * @param array $options
     *
     * @return void
     */
    protected function addArgumentsToForm(FormBuilderInterface $builder, array $sprykDefinition, array $options): void
    {
        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if ($argumentName == 'module' || $argumentName === 'organization') {
                continue;
            }

            if (isset($argumentDefinition['value']) || isset($argumentDefinition['callbackOnly']) || isset($argumentDefinition['default'])) {
                continue;
            }

            if (isset($argumentDefinition['type'])) {
                $typeOptions = $options;
                if (isset($argumentDefinition['isOptional'])) {
                    $typeOptions['required'] = false;
                }
                $builder->add($argumentName, 'Spryker\\Zed\\SprykGui\\Communication\\Form\\Type\\' . $argumentDefinition['type'] . 'Type', $typeOptions);

                continue;
            }

            $type = TextType::class;
            if (isset($argumentDefinition['multiline'])) {
                $type = TextareaType::class;
            }

            $typeOptions = [
                'attr' => [
                    'class' => $argumentName,
                ],
            ];
            if (isset($argumentDefinition['isOptional'])) {
                $typeOptions['required'] = false;
            }

            $builder->add($argumentName, $type, $typeOptions);
        }
    }

    /**
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(array $options): ModuleTransfer
    {
        return $options[static::MODULE];
    }

    /**
     * @param string $spryk
     *
     * @return array
     */
    protected function getSprykDefinition(string $spryk): array
    {
        $sprykDefinitions = (new SprykFacade())->getSprykDefinitions();

        return $sprykDefinitions[$spryk];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCreateTemplateButton(FormBuilderInterface $builder): self
    {
        $builder->add('create', SubmitType::class, [
            'label' => 'Create Template',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRunSprykButton(FormBuilderInterface $builder): self
    {
        $builder->add('run', SubmitType::class, [
            'label' => 'Run Spryk',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);

        return $this;
    }
}
