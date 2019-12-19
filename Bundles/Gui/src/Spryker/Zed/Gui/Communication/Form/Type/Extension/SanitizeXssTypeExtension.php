<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Type\Extension;

use Spryker\Zed\Gui\Communication\Form\EventListener\SanitizeXssListener;
use Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeServiceInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanitizeXssTypeExtension extends AbstractTypeExtension
{
    public const OPTION_SANITIZE_XSS = 'sanitize_xss';

    /**
     * @var \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(GuiToUtilSanitizeServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @return iterable
     */
    public function getExtendedTypes(): iterable
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
            ->setDefined(static::OPTION_SANITIZE_XSS)
            ->setDefault(static::OPTION_SANITIZE_XSS, false)
            ->setAllowedTypes(static::OPTION_SANITIZE_XSS, 'bool');
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
            $builder->addEventSubscriber(new SanitizeXssListener($this->utilSanitizeService));
        }
    }
}
