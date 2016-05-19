<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchPreferencesForm extends AbstractType
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'filterPreferences';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addAttributeName($builder)
            ->addAttributeType($builder)
            ->addIncludeForSuggestion($builder)
            ->addIncludeForSorting($builder)
            ->addIncludeForFullText($builder)
            ->addIncludeForFullTextBoosted($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeName(FormBuilderInterface $builder)
    {
        $builder->add('attribute_name', 'text', [
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeType(FormBuilderInterface $builder)
    {
        $builder->add('attribute_type', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => [
                'string' => 'String',
                'integer' => 'Integer',
                'none' => 'None',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIncludeForSuggestion(FormBuilderInterface $builder)
    {
        $builder->add('include_for_suggestion', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIncludeForSorting(FormBuilderInterface $builder)
    {
        $builder->add('include_for_sorting', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIncludeForFullText(FormBuilderInterface $builder)
    {
        $builder->add('include_for_full_text', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIncludeForFullTextBoosted(FormBuilderInterface $builder)
    {
        $builder->add('include_for_full_text_boosted', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getYesNoChoices()
    {
        return [
            'yes' => 'Yes',
            'no' => 'No',
        ];
    }

}
