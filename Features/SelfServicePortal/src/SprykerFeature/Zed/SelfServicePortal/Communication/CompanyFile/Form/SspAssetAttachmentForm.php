<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetAttachmentForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_SSP_ASSET_CHOICES = 'OPTION_ASSET_CHOICES';

    /**
     * @var string
     */
    public const OPTION_ID_FILE = 'OPTION_ID_FILE';

    /**
     * @var string
     */
    public const FIELD_ASSET_IDS = 'sspAssetIds';

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
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\AutocompleteAssetController::assetAction()
     *
     * @var string
     */
    protected const DATASOURCE_URL_PATH_ASSET = '/self-service-portal/autocomplete-asset/asset';

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
    protected const BLOCK_PREFIX = 'sspAssetFileAttachment';

    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_SSP_ASSET_CHOICES,
            static::OPTION_ID_FILE,
        ]);

        $resolver->setDefaults([
            static::OPTION_SSP_ASSET_CHOICES => [],
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
            ->addSspAssetField($builder, $options)
            ->addSaveField($builder);

        $this->addPreSetDataEventListeners($builder);
    }

    protected function addPreSetDataEventListeners(FormBuilderInterface $builder): void
    {
        $this->addPreSetDataEventToAssetField($builder);
    }

    protected function addPreSetDataEventToAssetField(FormBuilderInterface $builder): void
    {
        $builder->get(static::FIELD_ASSET_IDS)->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event): void {
                if (!$event->getData()) {
                    return;
                }

                $assetIds = (array)$event->getData();
                $assetTransfers = $this->getFactory()
                    ->createAssetAttachmentFormDataProvider()
                    ->getSspAssetCollectionByIds($assetIds);

                $choices = [];
                foreach ($assetTransfers as $assetTransfer) {
                    $choices[$assetTransfer->getNameOrFail() . ': ' . $assetTransfer->getReferenceOrFail()] = $assetTransfer->getIdSspAssetOrFail();
                }

                /** @var \Symfony\Component\Form\FormInterface $form */
                $form = $event->getForm()->getParent();

                $this->replaceAssetField($form, $choices);
            },
        );
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
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_ASSET,
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
    protected function addSspAssetField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ASSET_IDS, Select2ComboBoxType::class, [
            'label' => static::LABEL_ASSET,
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_SSP_ASSET_CHOICES],
            'attr' => [
                'data-autocomplete-url' => static::DATASOURCE_URL_PATH_ASSET,
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
                'data-qa' => 'attach-asset-submit-button',
            ],
        ]);

        return $this;
    }
}
