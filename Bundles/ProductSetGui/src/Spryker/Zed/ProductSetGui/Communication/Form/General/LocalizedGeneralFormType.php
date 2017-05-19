<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\General;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 */
class LocalizedGeneralFormType extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_URL = 'url';
    const FIELD_ORIGINAL_URL = 'original_url';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_FK_LOCALE = 'fk_locale';

    const URL_PATH_PATTERN = '#^/([^\s\\\\]+)$#i';

    const GROUP_UNIQUE_URL_CHECK = 'unique_url_check';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $originalUrl = $form->get(static::FIELD_ORIGINAL_URL)->getData();
                $updatedUrl = $form->get(static::FIELD_URL)->getData();

                if ($originalUrl !== $updatedUrl) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_URL_CHECK];
                }
                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addUrlField($builder)
            ->addOriginalUrlField($builder)
            ->addDescriptionField($builder)
            ->addFkLocaleField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, TextType::class, [
            'label' => 'Name *',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUrlField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_URL, TextType::class, [
            'label' => 'URL *',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => static::URL_PATH_PATTERN,
                    'message' => 'URL must start wth "/". "Space" and "\" characters are not allowed.',
                ]),
                new Callback([
                    'methods' => [
                        [$this, 'validateUniqueUrl']
                    ],
                    'groups' => [self::GROUP_UNIQUE_URL_CHECK],
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
    protected function addOriginalUrlField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ORIGINAL_URL, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_DESCRIPTION, TextareaType::class, [
            'label' => 'Description',
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
        $builder->add(self::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param string $url
     * @param ExecutionContextInterface $context
     *
     * @return void
     */
    public function validateUniqueUrl($url, ExecutionContextInterface $context)
    {
        if (!$url) {
            return;
        }

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        if ($this->getFactory()->getUrlFacade()->hasUrl($urlTransfer)) {
            $context->addViolation('URL is already used.');
        }
    }

}
