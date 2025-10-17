<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form;

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
class AttachFileForm extends AbstractType
{
    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'fileAttachment';

    /**
     * @var string
     */
    public const BUTTON_SAVE = 'save';

    /**
     * @var string
     */
    public const FIELD_ATTACHMENT_SCOPE = 'attachmentScope';

    /**
     * @var string
     */
    public const FIELD_COMPANY_IDS = 'companyIds';

    /**
     * @var string
     */
    public const FIELD_COMPANY_USER_IDS = 'companyUserIds';

    /**
     * @var string
     */
    public const FIELD_COMPANY_BUSINESS_UNIT_IDS = 'companyBusinessUnitIds';

    /**
     * @var string
     */
    public const FIELD_ASSET_IDS = 'sspAssetIds';

    /**
     * @var string
     */
    public const FIELD_COMPANY_USER_IDS_TO_BE_ATTACHED = 'companyUserIdsToBeAttached';

    /**
     * @var string
     */
    public const FIELD_COMPANY_USER_IDS_TO_BE_UNATTACHED = 'companyUserIdsToBeUnattached';

    /**
     * @var string
     */
    public const FIELD_COMPANY_IDS_TO_BE_ATTACHED = 'companyIdsToBeAttached';

    /**
     * @var string
     */
    public const FIELD_COMPANY_IDS_TO_BE_UNATTACHED = 'companyIdsToBeUnattached';

    /**
     * @var string
     */
    public const FIELD_BUSINESS_UNIT_IDS_TO_BE_ATTACHED = 'businessUnitIdsToBeAttached';

    /**
     * @var string
     */
    public const FIELD_BUSINESS_UNIT_IDS_TO_BE_UNATTACHED = 'businessUnitIdsToBeUnattached';

    /**
     * @var string
     */
    public const FIELD_ASSET_IDS_TO_BE_ATTACHED = 'sspAssetIdsToBeAttached';

    /**
     * @var string
     */
    public const FIELD_ASSET_IDS_TO_BE_UNATTACHED = 'sspAssetIdsToBeUnattached';

    /**
     * @var string
     */
    public const FIELD_MODEL_IDS = 'sspModelIds';

    /**
     * @var string
     */
    public const FIELD_MODEL_IDS_TO_BE_ATTACHED = 'sspModelIdsToBeAttached';

    /**
     * @var string
     */
    public const FIELD_MODEL_IDS_TO_BE_UNATTACHED = 'sspModelIdsToBeUnattached';

    /**
     * @var string
     */
    public const OPTION_ID_FILE = 'OPTION_ID_FILE';

    /**
     * @var string
     */
    public const FIELD_ASSET_FILE_UPLOAD = 'asset_file_upload';

    /**
     * @var string
     */
    public const FIELD_BUSINESS_UNIT_FILE_UPLOAD = 'business_unit_file_upload';

    /**
     * @var string
     */
    public const FIELD_COMPANY_USER_FILE_UPLOAD = 'company_user_file_upload';

    /**
     * @var string
     */
    public const FIELD_COMPANY_FILE_UPLOAD = 'company_file_upload';

    /**
     * @var string
     */
    public const FIELD_MODEL_FILE_UPLOAD = 'model_file_upload';

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
            static::OPTION_ID_FILE,
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
        $this
            ->addFileUploadFields($builder)
            ->addHiddenSelectionFields($builder)
            ->addSubmitField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileUploadFields(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_ASSET_FILE_UPLOAD, FileType::class, [
                'label' => 'Import Asset File',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-qa' => 'asset-file-upload',
                ],
            ])
            ->add(static::FIELD_BUSINESS_UNIT_FILE_UPLOAD, FileType::class, [
                'label' => 'Import Business Unit File',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-qa' => 'business-unit-file-upload',
                ],
            ])
            ->add(static::FIELD_COMPANY_USER_FILE_UPLOAD, FileType::class, [
                'label' => 'Import Company User File',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-qa' => 'company-user-file-upload',
                ],
            ])
            ->add(static::FIELD_COMPANY_FILE_UPLOAD, FileType::class, [
                'label' => 'Import Company File',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-qa' => 'company-file-upload',
                ],
            ])
            ->add(static::FIELD_MODEL_FILE_UPLOAD, FileType::class, [
                'label' => 'Import Model File',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-qa' => 'model-file-upload',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addHiddenSelectionFields(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_COMPANY_USER_IDS_TO_BE_ATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_COMPANY_USER_IDS_TO_BE_UNATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_COMPANY_IDS_TO_BE_ATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_COMPANY_IDS_TO_BE_UNATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_BUSINESS_UNIT_IDS_TO_BE_ATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_BUSINESS_UNIT_IDS_TO_BE_UNATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_ASSET_IDS_TO_BE_ATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_ASSET_IDS_TO_BE_UNATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_MODEL_IDS_TO_BE_ATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_MODEL_IDS_TO_BE_UNATTACHED, HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(static::FIELD_ATTACHMENT_SCOPE, HiddenType::class, [
            'mapped' => false,
            'attr' => [
                'data-qa' => 'attachmentScope',
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
                'data-qa' => 'attach-submit-button',
            ],
        ]);

        return $this;
    }
}
