<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;
use Spryker\Shared\Validator\Constraints\File;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Communication\FileImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantFileForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FILE = 'file';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => MerchantFileTransfer::class,
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
        parent::buildForm($builder, $options);

        $this->addFileField($builder, $options);

        $builder->addEventListener(FormEvents::POST_SUBMIT, $this->onPostSubmit(...));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFileField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FILE, FileType::class, [
            'label' => false,
            'required' => true,
            'mapped' => false,
            'constraints' => $this->getFileFieldConstraints($options),
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getFileFieldConstraints(array $options): array
    {
        return [
            new Required(),
            new NotBlank(),
            new File($this->getFileConstraintConfiguration($options)),
        ];
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    protected function getFileConstraintConfiguration(array $options): array
    {
        return [
            'maxSize' => $this->getConfig()->getMaxFileSize(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        /** @var \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer */
        $merchantFileTransfer = $form->getData();

        $merchantFileTransfer->setType(FileImportMerchantPortalGuiConfig::FILE_TYPE_DATA_IMPORT);

        $uploadedFile = $form->get(static::FIELD_FILE)->getData();
        if (!($uploadedFile instanceof UploadedFile)) {
            return;
        }

        /** @var string $fileContent */
        $fileContent = file_get_contents($uploadedFile->getPathname());

        $merchantFileTransfer->setOriginalFileName($uploadedFile->getClientOriginalName());
        $merchantFileTransfer->setSize($uploadedFile->getSize());
        $merchantFileTransfer->setContentType($uploadedFile->getMimeType());
        $merchantFileTransfer->setRealPath($uploadedFile->getRealPath());
        $merchantFileTransfer->setContent($fileContent);
    }
}
