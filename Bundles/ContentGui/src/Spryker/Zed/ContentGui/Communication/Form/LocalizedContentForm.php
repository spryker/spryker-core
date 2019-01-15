<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class LocalizedContentForm extends AbstractType
{
    public const FIELD_FK_LOCALE = 'fk_locale';
    public const FIELD_NAME = 'locale_name';

    public const FIELD_PARAMETERS = 'parameters';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(ContentForm::OPTION_CONTENT_ITEM_TERM_FORM);
        $resolver->setRequired(ContentForm::OPTION_CONTENT_ITEM_TRANSFORM);
        $resolver->setRequired(ContentForm::OPTION_CONTENT_ITEM_REVERS_TRANSFORM);

        $resolver->setDefaults([
            'required' => true,
            'validation_groups' => function (FormInterface $form) {
                $submittedData = $form->getData();

                if ($submittedData->getFkLocale() !== null) {
                    return null;
                }

                return [Constraint::DEFAULT_GROUP];
            },
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
        $this->addFkLocale($builder);
        $this->addLocaleName($builder);
        $this->addParameterCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocale(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addParameterCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_PARAMETERS,
            $options[ContentForm::OPTION_CONTENT_ITEM_TERM_FORM],
            [
                'label' => false,
            ]
        );

        $builder->get(static::FIELD_PARAMETERS)
            ->addModelTransformer(new CallbackTransformer(
                $options[ContentForm::OPTION_CONTENT_ITEM_TRANSFORM],
                $options[ContentForm::OPTION_CONTENT_ITEM_REVERS_TRANSFORM]
            ));

        return $this;
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return 'localized-content';
    }
}
