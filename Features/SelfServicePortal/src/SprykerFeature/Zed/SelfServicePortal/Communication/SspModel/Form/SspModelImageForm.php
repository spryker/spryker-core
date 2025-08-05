<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form;

use Spryker\Shared\Validator\Constraints\File;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class SspModelImageForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FILE = 'file';

    /**
     * @var string
     */
    public const FIELD_DELETE = 'delete';

    /**
     * @var string
     */
    public const OPTION_ORIGINAL_IMAGE_URL = 'imageUrl';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addFileField($builder, $options)
            ->addDeleteField($builder);
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
            'label' => 'Image',
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => $this->getConfig()->getSspModelDefaultImageFileMaxSize(),
                    'mimeTypes' => $this->getConfig()->getSspModelAllowedImageFileMimeTypes(),
                    'mimeTypesMessage' => 'Invalid file type. Allowed types: {{ types }}',
                ]),
            ],
            'multiple' => false,
            'attr' => [
                'accept' => implode(', ', $this->getConfig()->getSspModelAllowedImageFileMimeTypes()),
                'acceptExtensions' => implode(', ', $this->getConfig()->getSspModelAllowedFileExtensions()),
                'maxSize' => $this->convertToReadableSize($this->normalizeBinaryFormat($this->getConfig()->getSspModelDefaultImageFileMaxSize())),
                'maxTotalSize' => $this->convertToReadableSize($this->normalizeBinaryFormat($this->getConfig()->getSspModelDefaultImageFileMaxSize())),
                'descriptionMessage' => 'Max up to %size%. Allowed file formats %format%',
                'original-image-url' => $options[static::OPTION_ORIGINAL_IMAGE_URL] ?? null,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeleteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DELETE, CheckboxType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            static::OPTION_ORIGINAL_IMAGE_URL => null,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'model_image';
    }

    protected function convertToReadableSize(int $size): string
    {
        if ($size >= 1000 * 1000 * 1000) {
            return round($size / (1000 * 1000 * 1000), 2) . ' GB';
        } elseif ($size >= 1000 * 1000) {
            return round($size / (1000 * 1000), 2) . ' MB';
        } elseif ($size >= 1000) {
            return round($size / 1000, 2) . ' kB';
        } else {
            return $size . ' B';
        }
    }

    protected function normalizeBinaryFormat(int|string $totalMaxSize): int
    {
        if (is_string($totalMaxSize)) {
            if (stripos($totalMaxSize, 'k') !== false) {
                $totalMaxSize = (int)$totalMaxSize * 1000;
            } elseif (stripos($totalMaxSize, 'm') !== false) {
                $totalMaxSize = (int)$totalMaxSize * 1000 * 1000;
            } elseif (stripos($totalMaxSize, 'g') !== false) {
                $totalMaxSize = (int)$totalMaxSize * 1000 * 1000 * 1000;
            }
        }

        return (int)$totalMaxSize;
    }
}
