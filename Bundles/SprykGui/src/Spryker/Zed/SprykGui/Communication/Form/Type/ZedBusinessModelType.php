<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class ZedBusinessModelType extends AbstractType
{
    protected const MODULE = 'module';
    protected const SPRYK = 'spryk';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::MODULE,
            static::SPRYK,
        ]);

        $resolver->setDefaults([
            'data_class' => ArgumentCollectionTransfer::class,
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

        $classInformationCollectionTransfer = $this->getFacade()->getZedBusinessModels($moduleTransfer);

        $builder->add('className', ChoiceType::class, [
            'label' => false,
            'choices' => $classInformationCollectionTransfer->getClassInformations(),
            'choice_label' => 'name',
        ]);
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
}
