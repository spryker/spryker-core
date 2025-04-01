<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Form;

use DateTime;
use Generated\Shared\Transfer\ItemTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementFactory getFactory()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class ServiceItemSchedulerForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_SCHEDULED_AT = 'scheduledAt';

    /**
     * @var string
     */
    protected const FIELD_LABEL_SCHEDULED_AT = 'Date and time';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addScheduledAtField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addScheduledAtField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SCHEDULED_AT, DateTimeType::class, [
            'label' => static::FIELD_LABEL_SCHEDULED_AT,
            'widget' => 'single_text',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new GreaterThan([
                    'value' => (new DateTime('now'))->format(DateTime::ISO8601),
                    'message' => 'Service date must be in the future',
                ]),
            ],
            'attr' => [
                'data-qa' => 'reschedule-date',
            ],
            'property_path' => 'metadata.scheduledAt',
        ]);

        $this->addScheduledAtTransformer($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addScheduledAtTransformer(FormBuilderInterface $builder)
    {
        $builder->get(static::FIELD_SCHEDULED_AT)
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsString): DateTime|null {
                    if (!$dateAsString) {
                        return null;
                    }

                    return new DateTime($dateAsString);
                },
                function ($dateAsObject): string|null {
                    /** @var \DateTime|null $dateAsObject */
                    if (!$dateAsObject) {
                        return null;
                    }

                    return $dateAsObject->format(DateTime::ISO8601);
                },
            ));

        return $this;
    }
}
