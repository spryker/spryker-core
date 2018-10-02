<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Form;

use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\Gui\Communication\Form\Type\ParagraphType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 */
class CategoryType extends AbstractType
{
    public const FIELD_CMS_BLOCKS = 'id_cms_blocks';

    public const OPTION_CMS_BLOCK_LIST = 'option-cms-block-list';
    public const OPTION_CMS_BLOCK_POSITION_LIST = 'option-cms-block-position-list';
    public const OPTION_WRONG_CMS_BLOCK_LIST = 'option-wrong-cms-block-list';
    public const OPTION_ASSIGNED_CMS_BLOCK_TEMPLATE_LIST = 'option-assigned-cms-block-template-list';

    /**
     * @var array
     */
    public const SUPPORTED_CATEGORY_TEMPLATE_LIST = [
        CmsBlockCategoryConnectorConfig::CATEGORY_TEMPLATE_WITH_CMS_BLOCK,
        CmsBlockCategoryConnectorConfig::CATEGORY_TEMPLATE_ONLY_CMS_BLOCK,
    ];

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addWarningParagraphs($builder, $options[static::OPTION_WRONG_CMS_BLOCK_LIST]);
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
                'choices' => array_flip($choices),
                'choices_as_values' => true,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'data-assigned-cms-blocks' => $this->getFactory()->getUtilEncodingService()->encodeJson($assignedForPosition),
                    'data-supported-templates' => $this->getFactory()->getUtilEncodingService()->encodeJson(static::SUPPORTED_CATEGORY_TEMPLATE_LIST),
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
    protected function addWarningParagraphs(FormBuilderInterface $builder, array $wrongCmsBlockList)
    {
        if (!$wrongCmsBlockList) {
            return $this;
        }

        $builder->add(static::FIELD_CMS_BLOCKS . '_paragraph', ParagraphType::class, [
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
        $warningMessage .= 'The following blocks are deactivated or not valid: ';
        $warningMessage .= implode(', ', $wrongCmsBlockList);

        return $warningMessage;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms-blocks';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
