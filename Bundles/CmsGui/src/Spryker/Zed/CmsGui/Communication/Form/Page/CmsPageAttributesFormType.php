<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CmsPageAttributesFormType extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_URL = 'url';
    const FIELD_LOCALE_NAME = 'localeName';
    const FIELD_ID_CMS_PAGE_LOCALIZED_ATTRIBUTES = 'idCmsPageLocalizedAttributes';

    const OPTION_AVAILABLE_LOCALES = 'option_available_locales';

    const URL_PATH_PATTERN = '#^[a-z/\-0-9\.]+$#i';

    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $urlConstraint;

    /**
     * @param \Symfony\Component\Validator\Constraint $urlConstraint
     */
    public function __construct(Constraint $urlConstraint)
    {
        $this->urlConstraint = $urlConstraint;
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
            ->addIdCmsLocalizedAttributes($builder)
            ->addUrlField($builder)
            ->addCmsLocaleNameField($builder)
            ->addFieldLocalName($builder);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'updateUrlPrefix']
        );
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function updateUrlPrefix(FormEvent $event)
    {
        $cmsPageAttributesTransfer = $this->getCmsPageAttributesTransfer($event);
        if (!$cmsPageAttributesTransfer) {
            return;
        }

        $urlWithouPrefix = preg_replace(
            '#^' . $cmsPageAttributesTransfer->getUrlPrefix() . '#i',
            '',
            $cmsPageAttributesTransfer->getUrl()
        );
        $cmsPageAttributesTransfer->setUrl($urlWithouPrefix);

        $event->setData($cmsPageAttributesTransfer);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name *',
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
    protected function addCmsLocaleNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE_NAME, HiddenType::class);

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
            'label' => 'URL *',
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => static::URL_PATH_PATTERN,
                    'message' => 'Invalid path provided. Allowed characters [a-z], [0-9], -, /, .',
                ]),
                $this->urlConstraint,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldLocalName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsLocalizedAttributes(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CMS_PAGE_LOCALIZED_ATTRIBUTES, HiddenType::class);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_page_attributes';
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function getCmsPageAttributesTransfer(FormEvent $event)
    {
        return $event->getData();
    }

}
