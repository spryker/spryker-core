<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class AttachModelForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_SSP_MODEL_TRANSFER = 'OPTION_SSP_MODEL_TRANSFER';

    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'attachModel';

    /**
     * @var string
     */
    protected const BUTTON_SAVE = 'save';

    /**
     * @var string
     */
    protected const FIELD_ASSET_IDS_TO_BE_ASSIGNED = 'sspAssetIdsToBeAssigned';

    /**
     * @var string
     */
    protected const FIELD_ASSET_IDS_TO_BE_UNASSIGNED = 'sspAssetIdsToBeUnassigned';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_LIST_IDS_TO_BE_ASSIGNED = 'productListIdsToBeAssigned';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_LIST_IDS_TO_BE_UNASSIGNED = 'productListIdsToBeUnassigned';

    /**
     * @var string
     */
    public const FIELD_ASSET_FILE_UPLOAD = 'asset_file_upload';

    /**
     * @var string
     */
    public const FIELD_PRODUCT_LIST_FILE_UPLOAD = 'product_list_file_upload';

    /**
     * @var string
     */
    protected const LABEL_BUTTON_SAVE = 'Save';

    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_SSP_MODEL_TRANSFER,
        ]);

        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sspModelTransfer = $options[static::OPTION_SSP_MODEL_TRANSFER];

        $this
            ->addHiddenSelectionFields($builder)
            ->addFileUploadFields($builder)
            ->addSubmitField($builder);

        $builder->addModelTransformer($this->getFactory()->createSspModelCollectionRequestTransformer($sspModelTransfer));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addHiddenSelectionFields(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ASSET_IDS_TO_BE_ASSIGNED, HiddenType::class);

        $builder->add(static::FIELD_ASSET_IDS_TO_BE_UNASSIGNED, HiddenType::class);

        $builder->add(static::FIELD_PRODUCT_LIST_IDS_TO_BE_ASSIGNED, HiddenType::class);

        $builder->add(static::FIELD_PRODUCT_LIST_IDS_TO_BE_UNASSIGNED, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileUploadFields(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ASSET_FILE_UPLOAD, FileType::class, [
            'label' => 'Upload Asset Assignment File',
            'mapped' => false,
            'required' => false,
            'attr' => [
                'accept' => '.csv',
                'data-qa' => 'asset-file-upload',
            ],
        ]);

        $builder->add(static::FIELD_PRODUCT_LIST_FILE_UPLOAD, FileType::class, [
            'label' => 'Upload Product List Assignment File',
            'mapped' => false,
            'required' => false,
            'attr' => [
                'accept' => '.csv',
                'data-qa' => 'product-list-file-upload',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitField(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_SAVE, SubmitType::class, [
            'label' => static::LABEL_BUTTON_SAVE,
            'attr' => [
                'data-qa' => 'relation-submit-button',
            ],
        ]);

        return $this;
    }
}
