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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class SprykMainForm extends AbstractType
{
    protected const SPRYK = 'spryk';
    protected const MODULE = 'module';
    protected const DEPENDENT_MODULE = 'dependentModule';
    protected const ARGUMENTS = 'arguments';
    protected const TYPE = 'type';
    protected const MODULE_FILTER = 'moduleFilter';

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
        $typeToAddListenerTo = static::MODULE;

        $spryk = $options[static::SPRYK];
        $sprykDefinition = $this->getFacade()->getSprykDefinitionByName($spryk);

        if (isset($sprykDefinition[static::ARGUMENTS][static::MODULE][static::TYPE])) {
            $builder->add(static::MODULE, NewModuleType::class, ['sprykDefinition' => $sprykDefinition]);
            $this->addRunSprykButton($builder);
            $this->addCreateTemplateButton($builder);

            return;
        }

        $moduleOptions = [];
        if (isset($sprykDefinition[static::ARGUMENTS][static::MODULE][static::MODULE_FILTER])) {
            $moduleOptions[static::MODULE_FILTER] = $sprykDefinition[static::ARGUMENTS][static::MODULE][static::MODULE_FILTER];
        }

        $builder->add(static::MODULE, ModuleChoiceType::class, $moduleOptions);

        if (array_key_exists(static::DEPENDENT_MODULE, $sprykDefinition[static::ARGUMENTS])) {
            $dependentModuleOptions = [];
            if (isset($sprykDefinition[static::ARGUMENTS][static::DEPENDENT_MODULE][static::MODULE_FILTER])) {
                $dependentModuleOptions[static::MODULE_FILTER] = $sprykDefinition[static::ARGUMENTS][static::DEPENDENT_MODULE][static::MODULE_FILTER];
            }
            $builder->add(static::DEPENDENT_MODULE, ModuleChoiceType::class, $dependentModuleOptions);

            $typeToAddListenerTo = static::DEPENDENT_MODULE;
        }

        $this->addNextButton($builder);

        $builder->get($typeToAddListenerTo)->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options, $builder) {
            $form = $event->getForm()->getParent();
            $moduleTransfer = $this->getModuleTransferFromForm($form);

            if ($moduleTransfer->getName() && ($moduleTransfer->getOrganization() && $moduleTransfer->getOrganization()->getName())) {
                $form->remove('next');

                if ($form->has(static::DEPENDENT_MODULE)) {
                    $dependentModuleForm = $form->get(static::DEPENDENT_MODULE);
                    $dependentModuleTransfer = $dependentModuleForm->getData();

                    $moduleTransfer->setDependentModule($dependentModuleTransfer);
                }

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
        });
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransferFromForm(FormInterface $form): ModuleTransfer
    {
        return $form->get(static::MODULE)->getData();
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
