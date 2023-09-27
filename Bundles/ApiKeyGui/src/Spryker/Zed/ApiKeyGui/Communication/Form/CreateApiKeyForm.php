<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ApiKeyGui\Communication\ApiKeyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig getConfig()
 */
class CreateApiKeyForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const LABEL_NAME = 'Name';

    /**
     * @var string
     */
    protected const FIELD_EXPIRATION = 'valid_to';

    /**
     * @var string
     */
    protected const LABEL_EXPIRATION = 'Valid to';

    /**
     * @var string
     */
    protected const VALIDITY_DATE_FORMAT = 'Y-m-d';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'api-key';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ApiKeyTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addExpirationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => static::LABEL_NAME,
                'required' => true,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExpirationField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_EXPIRATION,
            DateType::class,
            [
                'label' => 'Valid To',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'js-valid-to-date-picker safe-datetime',
                ],
            ],
        );

        $this->addDateTimeTransformer(static::FIELD_EXPIRATION, $builder);

        return $this;
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addDateTimeTransformer(string $fieldName, FormBuilderInterface $builder): void
    {
        $builder
            ->get($fieldName)
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsString) {
                    if (!$dateAsString) {
                        return null;
                    }

                    return new DateTime($dateAsString);
                },
                function ($dateAsObject) {
                    if (!$dateAsObject) {
                        return null;
                    }

                    return $dateAsObject->format(static::VALIDITY_DATE_FORMAT);
                },
            ));
    }
}
