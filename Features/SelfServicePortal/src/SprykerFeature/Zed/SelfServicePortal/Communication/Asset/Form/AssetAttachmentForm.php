<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AssetAttachmentForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_ASSET_CHOICES = 'OPTION_ASSET_CHOICES';

    /**
     * @var string
     */
    public const OPTION_ID_FILE = 'OPTION_ID_FILE';

    /**
     * @var string
     */
    public const FIELD_ASSET_IDS = 'assetIds';

    /**
     * @var string
     */
    public const FIELD_SAVE = 'save';

    /**
     * @var string
     */
    protected const LABEL_ASSET = 'Asset';

    /**
     * @var string
     */
    protected const LABEL_SAVE = 'Save';

    /**
     * @var string
     */
    protected const DATASOURCE_URL_ASSET = '/self-service-portal/autocomplete-asset/asset';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SEARCH = 'Start typing to search...';

    /**
     * @var int
     */
    protected const DATASOURCE_MIN_CHARACTERS = 4;

    /**
     * @var string
     */
    public const FORM_NAME = 'asset-attachment-form';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_ASSET_CHOICES,
            static::OPTION_ID_FILE,
        ]);

        $resolver->setDefaults([
            static::OPTION_ASSET_CHOICES => [],
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
            ->addAssetField($builder, $options)
            ->addSaveField($builder);

        $actionUrl = Url::generate('/self-service-portal/attach-file/index', ['id-file' => $options[static::OPTION_ID_FILE]]);
        $builder->setAction($actionUrl);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, int> $choices
     *
     * @return void
     */
    protected function replaceAssetField(FormInterface $form, array $choices): void
    {
        $form->add(static::FIELD_ASSET_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_ASSET,
            'multiple' => true,
            'required' => false,
            'choices' => $choices,
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_ASSET,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'asset-field',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAssetField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ASSET_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_ASSET,
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_ASSET_CHOICES],
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_ASSET,
                'data-minimum-input-length' => static::DATASOURCE_MIN_CHARACTERS,
                'data-placeholder' => static::PLACEHOLDER_SEARCH,
                'data-qa' => 'asset-field',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSaveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SAVE, SubmitType::class, [
            'label' => static::LABEL_SAVE,
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }
}
