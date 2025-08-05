<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class TriggerEventForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NAME_EVENT = 'event';

    /**
     * @var string
     */
    public const OPTION_EVENT_NAMES = 'OPTION_EVENT_NAMES';

    /**
     * @var string
     */
    protected const LABEL_EVENT = 'Event';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_EVENT_NAMES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addEventTriggerButtons($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addEventTriggerButtons(FormBuilderInterface $builder, array $options)
    {
        foreach ($options[static::OPTION_EVENT_NAMES] as $eventName) {
            $builder->add($eventName, SubmitType::class, [
                'label' => $eventName,
                'attr' => [
                    'name' => $eventName,
                ],
            ]);
        }

        return $this;
    }
}
