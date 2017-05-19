<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\General;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class GeneralFormType extends AbstractType
{

    const FIELD_LOCALIZED_GENERAL_FORM_COLLECTION = 'localized_general_form_collection';
    const FIELD_IS_ACTIVE = 'is_active';
    const FIELD_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addProductSetDataFieldCollection($builder)
            ->addIsActiveField($builder)
            ->addIdProductSetField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductSetDataFieldCollection(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LOCALIZED_GENERAL_FORM_COLLECTION, CollectionType::class, [
            'type' => LocalizedGeneralFormType::class,

            'constraints' => [
                new Callback([
                    'methods' => [
                        function ($localizedGeneralForms, ExecutionContextInterface $context) {
                            $uniqueUrls = [];
                            foreach ($localizedGeneralForms as $localizedGeneralForm) {
                                $url = $localizedGeneralForm[LocalizedGeneralFormType::FIELD_URL];
                                if (in_array($url, $uniqueUrls)) {
                                    $context->addViolation('URLs must be different for each locale.');
                                    break;
                                }
                                $uniqueUrls[] = $url;
                            }
                        },
                    ],
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Active',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductSetField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_SET, HiddenType::class);

        return $this;
    }

}
