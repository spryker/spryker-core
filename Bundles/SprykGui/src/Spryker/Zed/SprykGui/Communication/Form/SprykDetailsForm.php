<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Spryk\SprykFacade;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SprykGui\Communication\Form\Type\ArgumentCollectionType;
use Spryker\Zed\SprykGui\Communication\Form\Type\OutputChoiceType;
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
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::SPRYK,
            static::MODULE,
        ]);

        $resolver->setDefaults([
            'classNameChoices' => [],
            'outputChoices' => [],
            'argumentChoices' => [],
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
        $sprykDefinition = $this->getSprykDefinition($options[static::SPRYK]);

        $filteredArguments = $this->getRelevantArguments($sprykDefinition['arguments']);

        $this->addArgumentsToForm($builder, $filteredArguments, $options);
    }

    /**
     * @param array $arguments
     *
     * @return array
     */
    protected function getRelevantArguments(array $arguments): array
    {
        $filteredArguments = [];
        foreach ($arguments as $argumentName => $argumentDefinition) {
            if (in_array($argumentName, ['module', 'organization']) || !$this->requiresUserInput($argumentDefinition)) {
                continue;
            }
            $filteredArguments[$argumentName] = $argumentDefinition;
        }

        return $filteredArguments;
    }

    /**
     * @param array|null $argumentDefinition
     *
     * @return bool
     */
    protected function requiresUserInput(?array $argumentDefinition): bool
    {
        return (!isset($argumentDefinition['value']) && !isset($argumentDefinition['callbackOnly']) && !isset($argumentDefinition['default']));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $arguments
     * @param array $options
     *
     * @return void
     */
    protected function addArgumentsToForm(FormBuilderInterface $builder, array $arguments, array $options): void
    {
        foreach ($arguments as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['type'])) {
                $typeOptions = $this->getFormTypeOptions($options, $argumentDefinition);
                if (isset($argumentDefinition['isOptional'])) {
                    $typeOptions['required'] = false;
                }
                $formTypeName = 'Spryker\\Zed\\SprykGui\\Communication\\Form\\Type\\' . $argumentDefinition['type'] . 'Type';

                $builder->add($argumentName, $formTypeName, $typeOptions);

                continue;
            }

            if ($argumentName === 'input' || $argumentName === 'constructorArguments') {
                $argumentCollectionTypeOptions = ['argumentChoices' => $options['argumentChoices']];
                unset($options['argumentChoices']);
                $builder->add($argumentName, ArgumentCollectionType::class, $argumentCollectionTypeOptions);

                continue;
            }

            if ($argumentName === 'output') {
                $outputChoiceTypeOptions = ['outputChoices' => $options['outputChoices']];
                unset($options['outputChoices']);
                $builder->add($argumentName, OutputChoiceType::class, $outputChoiceTypeOptions);

                continue;
            }

            $type = $this->getType($argumentDefinition);
            $typeOptions = $this->getTypeOptions($argumentName, $argumentDefinition);
            $builder->add($argumentName, $type, $typeOptions);
        }
    }

    /**
     * @param array $options
     * @param array $argumentDefinition
     *
     * @return array
     */
    protected function getFormTypeOptions(array $options, array $argumentDefinition): array
    {
        if (!isset($argumentDefinition['typeOptions'])) {
            return $options;
        }
        $typeOptions = [];
        foreach ($argumentDefinition['typeOptions'] as $typeOptionName) {
            if (isset($argumentDefinition[$typeOptionName])) {
                $typeOptions[$typeOptionName] = $argumentDefinition[$typeOptionName];
            }
            if (isset($options[$typeOptionName])) {
                $typeOptions[$typeOptionName] = $options[$typeOptionName];
            }
        }

        return $typeOptions;
    }

    /**
     * @param array|null $argumentDefinition
     *
     * @return string
     */
    protected function getType(?array $argumentDefinition): string
    {
        $type = TextType::class;
        if (isset($argumentDefinition['multiline'])) {
            $type = TextareaType::class;
        }

        return $type;
    }

    /**
     * @param string $argumentName
     * @param array|null $argumentDefinition
     *
     * @return array
     */
    protected function getTypeOptions(string $argumentName, ?array $argumentDefinition): array
    {
        $typeOptions = [
            'attr' => [
                'class' => $argumentName,
            ],
        ];
        if (isset($argumentDefinition['isOptional'])) {
            $typeOptions['required'] = false;
        }

        return $typeOptions;
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
