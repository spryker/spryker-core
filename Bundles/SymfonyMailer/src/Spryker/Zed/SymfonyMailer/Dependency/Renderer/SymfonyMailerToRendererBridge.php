<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Dependency\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;

class SymfonyMailerToRendererBridge implements SymfonyMailerToRendererInterface
{
    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct($twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param string $template
     * @param array<string, mixed> $context
     *
     * @return string
     */
    public function render(string $template, array $context = []): string
    {
        return $this->twigEnvironment->render($template, $context);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function setLocaleTransfer(LocaleTransfer $localeTransfer): void
    {
        /** @var \Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin $translator */
        $translator = $this->twigEnvironment->getExtension(TwigTranslatorPlugin::class);
        $translator->setLocaleTransfer($localeTransfer);
    }
}
