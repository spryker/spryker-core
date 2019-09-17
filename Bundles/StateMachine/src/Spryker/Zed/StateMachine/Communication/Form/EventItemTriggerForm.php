<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Form;

use Generated\Shared\Transfer\EventItemTriggerFormDataTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 */
class EventItemTriggerForm extends AbstractType
{
    public const OPTION_EVENT = 'OPTION_EVENT';
    public const OPTION_SUBMIT_BUTTON_CLASS = 'OPTION_SUBMIT_BUTTON_CLASS';
    public const OPTION_REDIRECT = 'OPTION_REDIRECT';

    protected const BUTTON_SUBMIT = 'submit';

    protected const ACTION_ROUTE = '/state-machine/trigger/submit-trigger-event-item';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addStateMachineNameField($builder)
            ->addProcessNameField($builder)
            ->addIdentifierField($builder)
            ->addRedirectField($builder)
            ->addSubmitButton($builder, $options)
            ->setAction($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStateMachineNameField(FormBuilderInterface $builder)
    {
        $builder->add(EventItemTriggerFormDataTransfer::STATE_MACHINE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProcessNameField(FormBuilderInterface $builder)
    {
        $builder->add(EventItemTriggerFormDataTransfer::PROCESS_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdentifierField(FormBuilderInterface $builder)
    {
        $builder->add(EventItemTriggerFormDataTransfer::IDENTIFIER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRedirectField(FormBuilderInterface $builder)
    {
        $builder->add(EventItemTriggerFormDataTransfer::REDIRECT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSubmitButton(FormBuilderInterface $builder, array $options)
    {
        $fieldOptions = [
            'label' => $options[static::OPTION_EVENT],
        ];

        if ($options[static::OPTION_SUBMIT_BUTTON_CLASS]) {
            $fieldOptions['attr'] = [
                'class' => $options[static::OPTION_SUBMIT_BUTTON_CLASS],
            ];
        }

        $builder->add(static::BUTTON_SUBMIT, SubmitType::class, $fieldOptions);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function setAction(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction(
            $this->createAction($options)
        );

        return $this;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function createAction(array $options): string
    {
        return Url::generate(static::ACTION_ROUTE, [EventItemTriggerFormDataTransfer::REDIRECT => $options[static::OPTION_REDIRECT]]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventItemTriggerFormDataTransfer::class,
            static::OPTION_EVENT => null,
            static::OPTION_SUBMIT_BUTTON_CLASS => null,
            static::OPTION_REDIRECT => null,
        ]);
    }
}
