<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterForm extends AbstractType
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'filter';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addAttributeName($builder);
        $this->addType($builder);
        $this->addFilterType($builder);
        $this->addIncludeForSuggestion($builder);
        $this->addIncludeForSorting($builder);
        $this->addIncludeForFullText($builder);
        $this->addIncludeForFullTextBoosted($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addAttributeName(FormBuilderInterface $builder)
    {
        $builder->add('attribute_name', 'text', [
            'disabled' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addType(FormBuilderInterface $builder)
    {
        $builder->add('type');
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addFilterType(FormBuilderInterface $builder)
    {
        $builder->add('filter_type', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => [
                'multi_select' => 'Multi Select',
                'single_select' => 'Single Select',
                'range' => 'Range',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addIncludeForSuggestion(FormBuilderInterface $builder)
    {
        $builder->add('include_for_suggestion', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addIncludeForSorting(FormBuilderInterface $builder)
    {
        $builder->add('include_for_sorting', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addIncludeForFullText(FormBuilderInterface $builder)
    {
        $builder->add('include_for_full_text', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @return void
     */
    protected function addIncludeForFullTextBoosted(FormBuilderInterface $builder)
    {
        $builder->add('include_for_full_text_boosted', 'choice', [
            'label' => '',
            'placeholder' => 'Select one',
            'choices' => $this->getYesNoChoices(),
        ]);
    }

    protected function getYesNoChoices()
    {
        return [
            'yes' => 'Yes',
            'no' => 'No',
        ];
    }

}
