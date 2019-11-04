<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockCmsGui\Communication\CmsSlotBlockCmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockCmsGui\CmsSlotBlockCmsGuiConfig getConfig()
 */
class CmsPageConditionForm extends AbstractType
{
    public const OPTION_PAGE_ARRAY = 'option-page-array';

    /**
     * @uses \Spryker\Shared\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorConfig::CONDITION_KEY
     */
    protected const FIELD_CMS_PAGE = 'cms_page';
    protected const FIELD_ALL = 'all';
    protected const FIELD_PAGE_IDS = 'pageIds';

    protected const LABEL_CMS_PAGES = 'CMS Pages';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCmsPageField($builder)
            ->addAllField($builder)
            ->addPageIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCmsPageField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CMS_PAGE, FormType::class, [
            'label' => false,
            'error_mapping' => [
                '.' => static::FIELD_PAGE_IDS,
            ],
            'constraints' => [
                $this->getFactory()->createCmsPageConditionsConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder)
    {
        $builder->get(static::FIELD_CMS_PAGE)->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => [
                'All CMS Pages' => true,
                'Specific CMS Pages' => false,
            ],
            'choice_value' => function ($choice) {
                return $choice ?? true;
            },
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
        $builder->get(static::FIELD_CMS_PAGE)->add(static::FIELD_PAGE_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_PAGE_ARRAY],
            'required' => false,
            'multiple' => true,
            'label' => static::LABEL_CMS_PAGES,
        ]);

        return $this;
    }
}
