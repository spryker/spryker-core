<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This Type can be used to select a class which is needed by a spryk.
 *
 * To use this for a spryk you need to define in the spryk yml a choiceLoader and type.
 * The `choiceLoader` class needs to be added to the ChoiceLoaderComposite in the SprykGuiBusinessFactory.
 * The `type` has to be `ClassNameChoice`.
 *
 * Example (*.yml):
 *
 * controller:
 *   choiceLoader: ZedCommunicationControllerChoiceLoader
 *   type: ClassNameChoice
 *
 * When the spryk has this configuration this type will be used and the choices will be loaded from the defined choiceLoader.
 *
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class ClassNameChoiceType extends AbstractType
{
    protected const MODULE = 'module';
    protected const SPRYK = 'spryk';
    protected const CHOICE_LOADER = 'choiceLoader';

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
            static::CHOICE_LOADER,
        ]);

        $resolver->setDefaults([
            'choices' => function (Options $options) {
                $moduleTransfer = $this->getModuleTransfer($options);
                $classInformationTransferCollection = $this->getFacade()
                    ->loadChoices($options[static::CHOICE_LOADER], $moduleTransfer);

                if (count($classInformationTransferCollection) === 0) {
                    return [];
                }

                return $classInformationTransferCollection;
            },
            'choice_label' => 'className',
            'placeholder' => 'Select',
        ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(Options $options): ModuleTransfer
    {
        return $options[static::MODULE];
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
