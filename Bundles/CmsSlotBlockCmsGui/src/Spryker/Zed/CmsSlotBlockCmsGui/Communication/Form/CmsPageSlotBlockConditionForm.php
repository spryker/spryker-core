<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form;

use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockCmsGui\Communication\CmsSlotBlockCmsGuiCommunicationFactory getFactory()
 */
class CmsPageSlotBlockConditionForm extends AbstractType
{
    public const OPTION_PAGE_ARRAY = 'option-page-array';
    public const OPTION_ALL_ARRAY = 'option-all-array';

    public const FIELD_ALL = CmsSlotBlockConditionTransfer::ALL;
    public const FIELD_CMS_PAGE_IDS = CmsSlotBlockConditionTransfer::CMS_PAGE_IDS;

    /**
     * @uses \Spryker\Shared\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorConfig::CONDITION_KEY
     */
    protected const FIELD_CMS_PAGE = 'cms_page';

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
            ->addAllField($builder->get(static::FIELD_CMS_PAGE), $options)
            ->addPageIdsField($builder->get(static::FIELD_CMS_PAGE), $options);
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
                '.' => static::FIELD_CMS_PAGE_IDS,
            ],
            'constraints' => [
                $this->getFactory()->createCmsPageConditionsConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => $options[static::OPTION_ALL_ARRAY],
            'choice_value' => function ($choice) {
                return $choice ?? true;
            },
            'choice_attr' => function ($choice, $key, $value) {
                return [
                    'data-disable' => $value,
                    'data-inputs' => $this->getFactory()->getUtilEncoding()->encodeJson([static::FIELD_CMS_PAGE_IDS]),
                ];
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
        $builder->add(static::FIELD_CMS_PAGE_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_PAGE_ARRAY],
            'required' => false,
            'multiple' => true,
            'label' => static::LABEL_CMS_PAGES,
        ]);

        return $this;
    }
}
