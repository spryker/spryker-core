<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CmsPageSlotBlockConditionForm extends AbstractType
{
    public const OPTION_PAGES = 'option-pages';

    public const FIELD_PAGE_IDS = 'pageIds';
    protected const FIELD_ALL = 'all';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAllField($builder)
            ->addPageIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => [
                'All CMS Pages' => true,
                'Specific CMS Pages' => false,
            ],
            'expanded' => true,
            'multiple' => false,
            'placeholder' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPageIdsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PAGE_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_PAGES],
            'required' => false,
            'multiple' => true,
            'label' => 'CMS Pages',
        ]);

        return $this;
    }
}
