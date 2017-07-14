<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Form;

use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\Gui\Communication\Form\Type\LabelType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

    const FIELD_CMS_BLOCKS = 'id_cms_blocks';

    const OPTION_CMS_BLOCK_LIST = 'option-cms-block-list';
    const OPTION_CMS_BLOCK_POSITION_LIST = 'option-cms-block-position-list';
    const OPTION_WRONG_CMS_BLOCK_LIST = 'option-wrong-cms-block-list';
    const OPTION_ASSIGNED_CMS_BLOCK_TEMPLATE_LIST = 'option-assigned-cms-block-template-list';

    /**
     * @var array
     */
    const SUPPORTED_CATEGORY_TEMPLATE_LIST = [
        CmsBlockCategoryConnectorConfig::CATEGORY_TEMPLATE_WITH_CMS_BLOCK,
        CmsBlockCategoryConnectorConfig::CATEGORY_TEMPLATE_ONLY_CMS_BLOCK,
    ];

    /**
     * @var UtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param UtilEncodingServiceInterface $encodingService
     */
    public function __construct(UtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms-blocks';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addWarningLabels($builder, $options[static::OPTION_WRONG_CMS_BLOCK_LIST]);
        $this->addCmsBlockFields(
            $builder,
            $options[static::OPTION_CMS_BLOCK_POSITION_LIST],
            $options[static::OPTION_CMS_BLOCK_LIST],
            $options[static::OPTION_ASSIGNED_CMS_BLOCK_TEMPLATE_LIST]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_CMS_BLOCK_LIST)
            ->setRequired(static::OPTION_CMS_BLOCK_POSITION_LIST)
            ->setRequired(static::OPTION_WRONG_CMS_BLOCK_LIST)
            ->setRequired(static::OPTION_ASSIGNED_CMS_BLOCK_TEMPLATE_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $positions
     * @param array $choices
     * @param array $assignedCmsBlocksForTemplates
     *
     * @return $this
     */
    protected function addCmsBlockFields(FormBuilderInterface $builder, array $positions, array $choices, array $assignedCmsBlocksForTemplates)
    {
        foreach ($positions as $idCmsBlockCategoryPosition => $positionName) {
            $assignedForPosition = isset($assignedCmsBlocksForTemplates[$idCmsBlockCategoryPosition]) ?
                $assignedCmsBlocksForTemplates[$idCmsBlockCategoryPosition] : [];

            $builder->add(static::FIELD_CMS_BLOCKS . '_' . $idCmsBlockCategoryPosition, Select2ComboBoxType::class, [
                'property_path' => static::FIELD_CMS_BLOCKS . '[' . $idCmsBlockCategoryPosition . ']',
                'label' => 'CMS Blocks: ' . $positionName,
                'choices' => $choices,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'data-assigned-cms-blocks' => $this->encodingService->encodeJson($assignedForPosition),
                    'data-supported-templates' => $this->encodingService->encodeJson(static::SUPPORTED_CATEGORY_TEMPLATE_LIST),
                ],
            ]);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $wrongCmsBlockList
     *
     * @return $this
     */
    protected function addWarningLabels(FormBuilderInterface $builder, array $wrongCmsBlockList)
    {
        if (empty($wrongCmsBlockList)) {
            return $this;
        }

        $builder->add(static::FIELD_CMS_BLOCKS . '_label', LabelType::class, [
            'text' => $this->formatWrongCmsBlockWarningMessage($wrongCmsBlockList),
        ]);

        return $this;
    }

    /**
     * @param array $wrongCmsBlockList
     *
     * @return string
     */
    protected function formatWrongCmsBlockWarningMessage(array $wrongCmsBlockList)
    {
        $warningMessage = '<i class="fa fa-exclamation-triangle"></i> ';
        $warningMessage .= 'The following blocks are not published: ';
        $warningMessage .= implode(', ', $wrongCmsBlockList);

        return $warningMessage;
    }

}
