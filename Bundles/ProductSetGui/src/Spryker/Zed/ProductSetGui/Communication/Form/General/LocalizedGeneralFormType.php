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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 */
class LocalizedGeneralFormType extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_URL = 'url';
    public const FIELD_URL_PREFIX = 'url_prefix';
    public const FIELD_ORIGINAL_URL = 'original_url';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_FK_LOCALE = 'fk_locale';

    public const URL_PATH_PATTERN = '#^([^\s\\\\]+)$#i';

    public const GROUP_UNIQUE_URL_CHECK = 'unique_url_check';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $originalUrl = $form->getData()[static::FIELD_ORIGINAL_URL];
                $updatedUrl = $form->getData()[static::FIELD_URL];

                if ($originalUrl !== $updatedUrl) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_URL_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
            'constraints' => [
                new Callback([
                    'callback' => [$this, 'validateUniqueUrl'],
                    'groups' => [static::GROUP_UNIQUE_URL_CHECK],
                ]),
            ],
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
            ->addUrlPrefixField($builder)
            ->addOriginalUrlField($builder)
            ->addDescriptionField($builder)
            ->addFkLocaleField($builder);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'onPreSetData']
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            [$this, 'onSubmit']
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
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
        $builder->add(static::FIELD_URL, TextType::class, [
            'label' => 'URL',
            'required' => true,
            'constraints' => [
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
    protected function addUrlPrefixField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_URL_PREFIX, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOriginalUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORIGINAL_URL, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DESCRIPTION, TextareaType::class, [
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
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param array $data
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function validateUniqueUrl(array $data, ExecutionContextInterface $context)
    {
        $url = $data[static::FIELD_URL];
        if (!$url) {
            return;
        }

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl(explode('?', $url)[0]);

        if ($this->getFactory()->getUrlFacade()->hasUrlCaseInsensitive($urlTransfer)) {
            $context
                ->buildViolation('URL is already used.')
                ->atPath('[' . static::FIELD_URL . ']')
                ->addViolation();
        }
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onPreSetData(FormEvent $event)
    {
        $this->formatUrlOnPreSetData($event);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onSubmit(FormEvent $event)
    {
        $this->formatUrlOnSubmit($event);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function formatUrlOnPreSetData(FormEvent $event)
    {
        $data = $event->getData();

        if (!isset($data[static::FIELD_URL])) {
            return;
        }

        $url = $data[static::FIELD_URL];
        $urlPrefix = $data[static::FIELD_URL_PREFIX];
        if ($urlPrefix) {
            $data[static::FIELD_URL] = preg_replace('#^' . $urlPrefix . '#i', '', $url);
        }

        $event->setData($data);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function formatUrlOnSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $url = $this->generateUrlFromFormData($data);
        $data[static::FIELD_URL] = $url;

        $event->setData($data);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function generateUrlFromFormData(array $data)
    {
        $url = $data[static::FIELD_URL];
        $urlPrefix = $data[static::FIELD_URL_PREFIX];

        if ($urlPrefix) {
            return $urlPrefix . $url;
        }

        return $url;
    }
}
