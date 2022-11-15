<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Business\Renderer;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToLocaleFacadeInterface;
use Spryker\Zed\SymfonyMailer\Dependency\Renderer\SymfonyMailerToRendererInterface;

class TwigRenderer implements RendererInterface
{
    /**
     * @var string
     */
    protected const LAYOUT_TEMPLATE_TEXT = 'SymfonyMailer/SymfonyMailer/layout/mail_layout.text.twig';

    /**
     * @var string
     */
    protected const LAYOUT_TEMPLATE_HTML = 'SymfonyMailer/SymfonyMailer/layout/mail_layout.html.twig';

    /**
     * @var string
     */
    protected const KEY_MAIL = 'mail';

    /**
     * @var \Spryker\Zed\SymfonyMailer\Dependency\Renderer\SymfonyMailerToRendererInterface
     */
    protected $renderer;

    /**
     * @var \Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\SymfonyMailer\Dependency\Renderer\SymfonyMailerToRendererInterface $renderer
     * @param \Spryker\Zed\SymfonyMailer\Dependency\Facade\SymfonyMailerToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SymfonyMailerToRendererInterface $renderer,
        SymfonyMailerToLocaleFacadeInterface $localeFacade
    ) {
        $this->renderer = $renderer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function render(MailTransfer $mailTransfer): void
    {
        $this->setupTranslation($mailTransfer);

        $this->renderTextTemplate($mailTransfer);
        $this->renderHtmlTemplate($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function setupTranslation(MailTransfer $mailTransfer): void
    {
        if (!$mailTransfer->getLocale()) {
            $mailTransfer->setLocale($this->localeFacade->getCurrentLocale());
        }

        $this->renderer->setLocaleTransfer($mailTransfer->getLocaleOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function renderTextTemplate(MailTransfer $mailTransfer): void
    {
        foreach ($mailTransfer->requireTemplates()->getTemplates() as $templateTransfer) {
            if ($templateTransfer->getIsHtml()) {
                continue;
            }

            $renderedContent = $this->renderer->render(static::LAYOUT_TEMPLATE_TEXT, [static::KEY_MAIL => $mailTransfer]);
            $templateTransfer->setContent($renderedContent);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function renderHtmlTemplate(MailTransfer $mailTransfer): void
    {
        foreach ($mailTransfer->requireTemplates()->getTemplates() as $templateTransfer) {
            if (!$templateTransfer->getIsHtml()) {
                continue;
            }

            $renderedContent = $this->renderer->render(static::LAYOUT_TEMPLATE_HTML, [static::KEY_MAIL => $mailTransfer]);
            $templateTransfer->setContent($renderedContent);
        }
    }
}
