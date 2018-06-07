<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use ArrayObject;
use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Generated\Shared\Transfer\ArgumentMetaTransfer;
use Generated\Shared\Transfer\ArgumentTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class ZedBusinessDependencyType extends AbstractType
{
    const MODULE_INFORMATION = 'moduleInformation';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::MODULE_INFORMATION,
            'spryk',
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
        $moduleTransfer = $this->getModuleTransfer($options);
        $className = sprintf('\%1$s\Zed\%2$s\Business\%2$sBusinessFactory', $moduleTransfer->getOrganization(), $moduleTransfer->getName());
        $factoryInformation = $this->getFacade()->getFactoryInformation($className);

        $methods = $factoryInformation->getMethods();

        $argumentCollectionTransfer = $this->buildArguments($methods, $moduleTransfer);
        $argumentOptions = [
            'entry_type' => ArgumentType::class,
            'entry_options' => ['label' => false, 'argumentCollectionTransfer' => $argumentCollectionTransfer],
            'allow_add' => true,
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'prototype',
            ],
        ];

        $builder->add('arguments', CollectionType::class, $argumentOptions);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MethodInformationTransfer[] $methodCollection
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ArgumentCollectionTransfer
     */
    protected function buildArguments(ArrayObject $methodCollection, ModuleTransfer $moduleTransfer): ArgumentCollectionTransfer
    {
        $argumentCollectionTransfer = new ArgumentCollectionTransfer();
        foreach ($methodCollection as $methodTransfer) {
            $argumentMetaTransfer = new ArgumentMetaTransfer();
            $argumentMetaTransfer->setMethod($methodTransfer->getName());

            $argumentTransfer = new ArgumentTransfer();
            $argumentTransfer->setName(sprintf(
                '%s (%sBusinessFactory::%s())',
                $methodTransfer->getReturnType()->getType(),
                $moduleTransfer->getName(),
                $methodTransfer->getName()
            ));
            $argumentTransfer->setType($methodTransfer->getReturnType()->getType());
            $argumentTransfer->setVariable($this->getVariableProposal($methodTransfer));

            $argumentTransfer->setArgumentMeta($argumentMetaTransfer);
            $argumentCollectionTransfer->addArgument($argumentTransfer);
        }

        return $argumentCollectionTransfer;
    }

    /**
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(array $options): ModuleTransfer
    {
        return $options[static::MODULE_INFORMATION];
    }

    /**
     * @param \Generated\Shared\Transfer\MethodInformationTransfer $methodTransfer
     *
     * @return string
     */
    protected function getVariableProposal(MethodInformationTransfer $methodTransfer): string
    {
        $typeFragments = explode('\\', $methodTransfer->getReturnType()->getType());
        $classOrInterfaceName = array_pop($typeFragments);
        $classOrInterfaceName = str_replace('Interface', '', $classOrInterfaceName);

        return '$' . lcfirst($classOrInterfaceName);
    }
}
