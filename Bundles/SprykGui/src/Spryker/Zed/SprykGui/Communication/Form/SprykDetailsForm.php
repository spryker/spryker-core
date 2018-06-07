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
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver|void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'spryk',
            'moduleInformation',
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
        $this->addMainSpryk($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addMainSpryk(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $sprykDefinition = $this->getSprykDefinition($options['spryk']);

        $sprykSubForm = $builder->getFormFactory()->createNamedBuilder($options['spryk']);

        $this->addArgumentsToForm($sprykDefinition, $sprykSubForm, $options);

        $builder->add($sprykSubForm);

        $this->addRunSprykButton($builder);
        $this->addCreateTemplateButton($builder);

        return $builder;
    }

    /**
     * @param array $sprykDefinition
     * @param \Symfony\Component\Form\FormBuilderInterface $sprykSubForm
     * @param array $options
     *
     * @return void
     */
    protected function addArgumentsToForm(array $sprykDefinition, FormBuilderInterface $sprykSubForm, array $options): void
    {
        $moduleTransfer = $this->getModuleTransfer($options);

        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['value']) || isset($argumentDefinition['callbackOnly'])) {
                continue;
            }

            if (isset($argumentDefinition['type'])) {
                $typeOptions = $options;
                if (isset($argumentDefinition['isOptional'])) {
                    $typeOptions['required'] = false;
                }
                $sprykSubForm->add($argumentName, 'Spryker\\Zed\\SprykGui\\Communication\\Form\\Type\\' . $argumentDefinition['type'] . 'Type', $typeOptions);

                continue;
            }

            $value = $this->getValue($argumentName, $moduleTransfer, (array)$argumentDefinition);

            $type = TextType::class;
            if (isset($argumentDefinition['multiline'])) {
                $type = TextareaType::class;
            }

            $typeOptions = [
                'data' => $value,
                'attr' => [
                    'class' => $argumentName,
                ],
            ];
            if (isset($argumentDefinition['isOptional'])) {
                $typeOptions['required'] = false;
            }

            $sprykSubForm->add($argumentName, $type, $typeOptions);
        }
    }

    /**
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(array $options): ModuleTransfer
    {
        return $options['moduleInformation'];
    }

    /**
     * @param string $argumentName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param array $argumentDefinition
     *
     * @return string
     */
    protected function getValue(string $argumentName, ModuleTransfer $moduleTransfer, array $argumentDefinition): string
    {
        if ($argumentName === 'module') {
            return $moduleTransfer->getName();
        }

        if ($argumentName === 'moduleOrganization') {
            return $moduleTransfer->getOrganization();
        }

        if (isset($argumentDefinition['default'])) {
            return $argumentDefinition['default'];
        }

        return '';
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
