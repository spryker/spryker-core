<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SprykForm extends AbstractType
{
    const SPRYK = 'spryk';

    /**
     * @var array
     */
    protected $spryksToRun = [];

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver|void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'spryk',
            'sprykDefinitions',
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
//        $this->addOthers($builder, $options);
        $this->addCreateTemplateButton($builder);
        $this->addRunSprykButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\SprykGui\Communication\Form\SprykForm
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
     * @return \Spryker\Zed\SprykGui\Communication\Form\SprykForm
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

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getSprykList(array $options): array
    {
        $sprykName = $this->getSprykToBuild($options);
        $this->buildSprykList($sprykName, $options);

        return $this->spryksToRun;
    }

    /**
     * @param string $sprykName
     * @param array $options
     *
     * @return void
     */
    protected function buildSprykList(string $sprykName, array $options)
    {
        if (isset($this->spryksToRun[$sprykName])) {
            return;
        }

        $sprykDefinition = $this->getSprykDefinitionByName($sprykName, $options);

        $this->addPreSpryks($sprykDefinition, $options);
        $this->spryksToRun[$sprykName] = $sprykName;
        $this->addPostSpryks($sprykDefinition, $options);
    }

    /**
     * @param array $sprykDefinition
     * @param array $options
     *
     * @return void
     */
    protected function addPreSpryks(array $sprykDefinition, array $options)
    {
        if (!isset($sprykDefinition['preSpryks'])) {
            return;
        }
        foreach ($sprykDefinition['preSpryks'] as $sprykName) {
            $this->buildSprykList($sprykName, $options);
        }
    }

    /**
     * @param array $sprykDefinition
     * @param array $options
     *
     * @return void
     */
    protected function addPostSpryks(array $sprykDefinition, array $options)
    {
        if (!isset($sprykDefinition['postSpryks'])) {
            return;
        }
        foreach ($sprykDefinition['postSpryks'] as $sprykName) {
            $this->buildSprykList($sprykName, $options);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addMainSpryk(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $sprykName = $this->getSprykToBuild($options);
        $sprykDefinition = $this->getSprykDefinitionByName($sprykName, $options);

        $mainSprykForm = $builder->getFormFactory()->createNamedBuilder($sprykName);

        $this->addArgumentsToForm($sprykDefinition, $mainSprykForm, $options);

        $builder->add($mainSprykForm);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addOthers(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $spryksToBuild = $this->getSprykList($options);

        foreach ($spryksToBuild as $sprykName) {
            if ($builder->has($sprykName)) {
                continue;
            }

            $sprykDefinition = $this->getSprykDefinitionByName($sprykName, $options);
            $sprykForm = $builder->getFormFactory()->createNamedBuilder($sprykName);
            $this->addArgumentsToForm($sprykDefinition, $sprykForm, $options);

            $builder->add($sprykForm);
        }

        return $builder;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getSprykDefinitions(array $options): array
    {
        $sprykDefinitions = $options['sprykDefinitions'];

        return $sprykDefinitions;
    }

    /**
     * @param string $sprykName
     * @param array $options
     *
     * @return array
     */
    protected function getSprykDefinitionByName(string $sprykName, array $options): array
    {
        $sprykDefinitions = $this->getSprykDefinitions($options);

        return $sprykDefinitions[$sprykName];
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getSprykToBuild(array $options): string
    {
        return $options['spryk'];
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
        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['value']) || isset($argumentDefinition['callbackOnly'])) {
                continue;
            }

            $value = '';

            if (isset($argumentDefinition['default'])) {
                $value = $argumentDefinition['default'];
            }

            $type = TextType::class;
            if (isset($argumentDefinition['multiline'])) {
                $type = TextareaType::class;
            }

            $sprykSubForm->add($argumentName, $type, [
                'data' => $value,
                'attr' => [
                    'class' => $argumentName,
                ],
            ]);
        }
    }
}
