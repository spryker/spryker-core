<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Plugin;

use Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer;
use Spryker\Zed\CategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockGuiExtension\Communication\Plugin\CmsSlotBlockGuiConditionFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class CategorySlotBlockConditionFormPlugin extends AbstractPlugin implements CmsSlotBlockGuiConditionFormPluginInterface
{
    /**
     * @uses \Spryker\Shared\CategoryGui\CategoryGuiConstants::CONDITION_KEY
     */
    protected const CONDITION_KEY = 'category';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer $cmsSlotTemplateConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(CmsSlotTemplateConfigurationTransfer $cmsSlotTemplateConfigurationTransfer): bool
    {
        return in_array(static::CONDITION_KEY, $cmsSlotTemplateConfigurationTransfer->getConditions(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function addConditionForm(FormBuilderInterface $builder): void
    {
        $builder->add(static::CONDITION_KEY, FormType::class, [
            'label' => false,
            'error_mapping' => [
                '.' => CategorySlotBlockConditionForm::FIELD_CATEGORY_IDS,
            ],
            'constraints' => [
                $this->getFactory()->createCategoryConditionsConstraint(),
            ],
        ]);

        $categorySlotBlockDataProvider = $this->getFactory()->createCategorySlotBlockDataProvider();
        $categorySlotBlockConditionForm = $this->getFactory()->createCategorySlotBlockConditionForm();
        $categorySlotBlockConditionForm->buildForm(
            $builder->get(static::CONDITION_KEY),
            $categorySlotBlockDataProvider->getOptions()
        );
    }
}
