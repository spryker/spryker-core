<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SprykGui\Communication\Form\Type\ModuleChoiceType;
use Spryker\Zed\SprykGui\Communication\Form\Type\NewModuleType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class SprykMainForm extends AbstractType
{
    protected const SPRYK = 'spryk';
    protected const MODULE = 'module';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    const DEPENDENT_MODULE = 'dependentModule';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
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
            $this->addRunSprykButton($builder);
            $this->addCreateTemplateButton($builder);

            return;
        }

        $moduleTransferCollection = $this->getFacade()->getModules();

        $builder->add(static::MODULE, ModuleChoiceType::class, [
            ModuleChoiceType::MODULE_TRANSFER_COLLECTION => $moduleTransferCollection,
        ]);

        if (array_key_exists(static::DEPENDENT_MODULE, $sprykDefinition['arguments'])) {
            $builder->add(static::DEPENDENT_MODULE, ModuleChoiceType::class, [
                ModuleChoiceType::MODULE_TRANSFER_COLLECTION => $moduleTransferCollection,
            ]);
        }

        $this->addNextButton($builder);

        $builder->get('module')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options, $builder) {
            $form = $event->getForm()->getParent();
            $moduleTransfer = $event->getForm()->getData();
            if ($moduleTransfer instanceof ModuleTransfer) {
                if ($moduleTransfer->getName() && ($moduleTransfer->getOrganization() && $moduleTransfer->getOrganization()->getName())) {
                    $form->remove('next');

                    $sprykDataProvider = $this->getFactory()->createSprykFormDataProvider();
                    $sprykDetailsForm = $builder->getFormFactory()
                        ->createNamedBuilder(
                            'sprykDetails',
                            SprykDetailsForm::class,
                            $sprykDataProvider->getData($options[static::SPRYK], $moduleTransfer),
                            $sprykDataProvider->getOptions($options[static::SPRYK], $moduleTransfer)
                        )->getForm();

                    $form->add($sprykDetailsForm);

                    $this->addRunSprykButton($form);
                    $this->addCreateTemplateButton($form);

                    return;
                }
            }
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     *
     * @return $this
     */
    protected function addCreateTemplateButton($builder): self
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
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     *
     * @return $this
     */
    protected function addRunSprykButton($builder): self
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
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     *
     * @return void
     */
    protected function addNextButton($builder): void
    {
        $builder->add('next', SubmitType::class, [
            'label' => 'Next step',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);
    }
}
