<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NavigationNodeLocalizedAttributesFormType extends AbstractType
{

    const FIELD_TITLE = 'title';
    const FIELD_FK_LOCALE = 'fk_locale';
    const FIELD_EXTERNAL_URL = 'external_url';
    const FIELD_FK_URL = 'fk_url';

    /**
     * @return string
     */
    public function getName()
    {
        return 'navigation_node_localized_attributes';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => NavigationNodeLocalizedAttributesTransfer::class,
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
        $this
            ->addTitleField($builder)
            ->addLocaleField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_TITLE, TextType::class, [
                'label' => 'Title',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

}
