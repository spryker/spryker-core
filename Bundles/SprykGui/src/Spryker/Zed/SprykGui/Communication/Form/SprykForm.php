<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
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
        $spryksToBuild = $this->getSprykList($options);

        $this->addMainSpryk($builder, $options);
        $this->addOthers($spryksToBuild, $builder, $options);
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

        $this->addArgumentsToForm($sprykDefinition, $mainSprykForm);

        $builder->add($mainSprykForm);

        return $builder;
    }

    /**
     * @param string[] $spryksToBuild
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addOthers(array $spryksToBuild, FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        foreach ($spryksToBuild as $sprykName) {
            if ($builder->has($sprykName)) {
                continue;
            }

            $sprykDefinition = $this->getSprykDefinitionByName($sprykName, $options);
            $sprykForm = $builder->getFormFactory()->createNamedBuilder($sprykName);
            $this->addArgumentsToForm($sprykDefinition, $sprykForm);

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
     *
     * @return void
     */
    protected function addArgumentsToForm(array $sprykDefinition, FormBuilderInterface $sprykSubForm): void
    {
        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['value'])) {
                continue;
            }
            $value = '';
            if (isset($argumentDefinition['default'])) {
                $value = $argumentDefinition['default'];
            }

            $sprykSubForm->add($argumentName, TextType::class, [
                'data' => $value,
                'attr' => [
                    'class' => $argumentName,
                ],
            ]);
        }
    }
}
