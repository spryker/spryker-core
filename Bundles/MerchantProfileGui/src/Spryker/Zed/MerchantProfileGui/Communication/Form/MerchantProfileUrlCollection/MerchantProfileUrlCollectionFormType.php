<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileUrlCollection;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileUrlCollectionFormType extends AbstractType
{
    public const FIELD_URL = 'url';
    protected const TEMPLATE_PATH = 'url';
    protected const FIELD_FK_LOCALE = 'fkLocale';
    protected const LABEL_URL = 'Profile URL';
    protected const URL_PATH_PATTERN = '#^([^\s\\\\]+)$#i';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                $this->getFactory()->createUniqueUrlConstraint(),
            ],
        ]);
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addUrlField($builder)
            ->addFkLocaleField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function getUrlTransfer(FormEvent $event): ?UrlTransfer
    {
        return $event->getData();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_URL, TextType::class, [
            'label' => static::LABEL_URL,
            'required' => false,
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Regex([
                    'pattern' => static::URL_PATH_PATTERN,
                    'message' => 'Invalid path provided. "Space" and "\" character is not allowed.',
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
    protected function addFkLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }
}
