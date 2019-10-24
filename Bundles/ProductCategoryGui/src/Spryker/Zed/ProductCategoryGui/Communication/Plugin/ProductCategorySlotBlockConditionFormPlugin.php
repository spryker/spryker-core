<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryGui\Communication\Plugin;

use Generated\Shared\Transfer\CmsSlotTemplateConfigurationTransfer;
use Spryker\Zed\CmsSlotBlockGuiExtension\Communication\Plugin\CmsSlotBlockGuiConditionFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductCategoryGui\Communication\ProductCategoryGuiCommunicationFactory getFactory()
 */
class ProductCategorySlotBlockConditionFormPlugin extends AbstractPlugin implements CmsSlotBlockGuiConditionFormPluginInterface
{
    /**
     * @uses \Spryker\Shared\ProductCategoryGui\ProductCategoryGuiConstants::CONDITION_KEY
     */
    protected const CONDITION_KEY = 'product';

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
                '.' => ProductCategorySlotBlockConditionForm::FIELD_ALL,
            ],
            'constraints' => [
                $this->getFactory()->createProductCategoryConditionsConstraint(),
            ],
        ]);

        $categorySlotBlockConditionForm = $this->getFactory()->createProductCategorySlotBlockConditionForm();
        $dataProvider = $this->getFactory()
            ->createProductCategorySlotBlockDataProvider();

        $categorySlotBlockConditionForm->buildForm(
            $builder->get(static::CONDITION_KEY),
            $dataProvider->getOptions()
        );
    }
}
