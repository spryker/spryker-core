<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Communication\FileImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantFileImportForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ENTITY_TYPE = 'entity_type';

    /**
     * @var string
     */
    public const FIELD_MERCHANT_FILE = 'merchantFile';

    /**
     * @var string
     */
    public const OPTION_TYPE_CHOICES = 'option_type_choices';

    /**
     * @var string
     */
    protected const LABEL_TYPE = 'File Type';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this
            ->addEntityTypeField($builder, $options)
            ->addMerchantFileField($builder, $options);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
            /** @var \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer */
            $merchantFileImportTransfer = $event->getData();

            $merchantFileImportTransfer->setStatus($this->getConfig()->getInitialFileImportStatus());
        });
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_TYPE_CHOICES,
        ]);

        $resolver->setDefaults([
            'data_class' => MerchantFileImportTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addEntityTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ENTITY_TYPE, SelectType::class, [
            'label' => static::LABEL_TYPE,
            'placeholder' => 'Select type',
            'choices' => $options[static::OPTION_TYPE_CHOICES],
            'required' => true,
            'constraints' => $this->getEntityTypeFieldConstraints($options),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addMerchantFileField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_MERCHANT_FILE, MerchantFileForm::class, [
            'label' => false,
            'mapped' => false,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getEntityTypeFieldConstraints(array $options): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Choice(['choices' => $options[static::OPTION_TYPE_CHOICES]]),
        ];
    }
}
