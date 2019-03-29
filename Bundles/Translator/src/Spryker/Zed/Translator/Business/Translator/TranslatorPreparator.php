<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Spryker\Shared\Kernel\Communication\Application;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

/**
 * @deprecated Will be removed without replacement.
 */
class TranslatorPreparator implements TranslatorPreparatorInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected $application;

    /**
     * @var \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    protected $translator;

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface $translator
     */
    public function __construct(
        Application $application,
        TranslatorInterface $translator
    ) {
        $this->application = $application;
        $this->translator = $translator;
    }

    /**
     * @return void
     */
    public function prepareTranslatorService(): void
    {
        $translator = $this->translator;
        $twig = $this->application->get('twig');

        $this->application->remove('twig');

        $twig->addExtension(new TranslationExtension($translator));

        $this->application->set('twig', $twig);
        $this->application->set('translator', $translator);
    }
}
