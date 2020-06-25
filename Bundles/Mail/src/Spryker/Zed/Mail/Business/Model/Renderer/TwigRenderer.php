<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface;

class TwigRenderer implements RendererInterface
{
    public const LAYOUT_TEMPLATE_TEXT = 'Mail/Mail/layout/mail_layout.text.twig';
    public const LAYOUT_TEMPLATE_HTML = 'Mail/Mail/layout/mail_layout.html.twig';

    /**
     * @var \Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface
     */
    protected $renderer;

    /**
     * @param \Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface $renderer
     */
    public function __construct(MailToRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function render(MailTransfer $mailTransfer)
    {
        $this->setupTranslation($mailTransfer);

        $this->renderAndAddTextTemplate($mailTransfer);
        $this->renderAndAddHtmlTemplate($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function setupTranslation(MailTransfer $mailTransfer)
    {
        if (!$mailTransfer->getLocale()) {
            $localeTransfer = $this->getCurrentLocaleTransfer();
            $mailTransfer->setLocale($localeTransfer);
        }

        $this->renderer->setLocaleTransfer($mailTransfer->getLocale());
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocaleTransfer()
    {
        return (new LocaleTransfer())
            ->setLocaleName(Store::getInstance()->getCurrentLocale());
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function renderAndAddTextTemplate(MailTransfer $mailTransfer)
    {
        foreach ($mailTransfer->requireTemplates()->getTemplates() as $templateTransfer) {
            if (!$templateTransfer->getIsHtml()) {
                $renderedContent = $this->renderer->render(static::LAYOUT_TEMPLATE_TEXT, ['mail' => $mailTransfer]);
                $templateTransfer->setContent($renderedContent);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function renderAndAddHtmlTemplate(MailTransfer $mailTransfer)
    {
        foreach ($mailTransfer->requireTemplates()->getTemplates() as $templateTransfer) {
            if ($templateTransfer->getIsHtml()) {
                $renderedContent = $this->renderer->render(self::LAYOUT_TEMPLATE_HTML, ['mail' => $mailTransfer]);
                $templateTransfer->setContent($renderedContent);
            }
        }
    }
}
