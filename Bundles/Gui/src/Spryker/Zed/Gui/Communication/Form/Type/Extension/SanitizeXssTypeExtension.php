<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Type\Extension;

use Spryker\Zed\Gui\Communication\Form\EventListener\SanitizeXssListener;
use Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanitizeXssTypeExtension extends AbstractTypeExtension
{
    public const OPTION_SANITIZE_XSS = 'sanitize_xss';
    public const OPTION_ALLOWED_ATTRIBUTES = 'allowed_attributes';
    public const OPTION_ALLOWED_HTML_TAGS = 'allowed_html_tags';

    /**
     * @var \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceInterface
     */
    protected $utilSanitizeXssService;

    /**
     * @param \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceInterface $utilSanitizeService
     */
    public function __construct(GuiToUtilSanitizeXssServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeXssService = $utilSanitizeService;
    }

    /**
     * @return iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            TextType::class,
            TextareaType::class,
        ];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined([
                static::OPTION_SANITIZE_XSS,
                static::OPTION_ALLOWED_ATTRIBUTES,
                static::OPTION_ALLOWED_HTML_TAGS,
            ])
            ->setDefaults([
                static::OPTION_SANITIZE_XSS => false,
                static::OPTION_ALLOWED_ATTRIBUTES => [],
                static::OPTION_ALLOWED_HTML_TAGS => [],
            ])
            ->setAllowedTypes(static::OPTION_SANITIZE_XSS, 'bool')
            ->setAllowedTypes(static::OPTION_ALLOWED_ATTRIBUTES, 'array')
            ->setAllowedTypes(static::OPTION_ALLOWED_HTML_TAGS, 'array');
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options[static::OPTION_SANITIZE_XSS]) {
            $builder->addEventSubscriber(new SanitizeXssListener($this->utilSanitizeXssService));
        }
    }
}
