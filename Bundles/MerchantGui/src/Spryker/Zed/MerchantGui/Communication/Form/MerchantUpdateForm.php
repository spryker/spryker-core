<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantUpdateForm extends MerchantCreateForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param int|null $currentMerchantId
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder, ?int $currentMerchantId = null)
    {
        $builder
            ->add(static::FIELD_MERCHANT_REFERENCE, TextType::class, [
                'label' => static::LABEL_MERCHANT_REFERENCE,
                'attr' => [
                    'readonly' => 'readonly',
                ],
                'disabled' => true,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($this->getFactory()->getMerchantUpdateFormViewExpanderPlugins() as $formViewExpanderPlugin) {
            $view = $formViewExpanderPlugin->expand($view, $form, $options);
        }
    }
}
