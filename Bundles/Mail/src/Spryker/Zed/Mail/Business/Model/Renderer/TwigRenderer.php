<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface;
use Spryker\Zed\Mail\Dependency\Facade\MailToStoreFacadeInterface;
use Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface;

class TwigRenderer implements RendererInterface
{
    /**
     * @deprecated Will be removed without replacement.
     */
    public const LAYOUT_TEMPLATE_TEXT = 'Mail/Mail/layout/mail_layout.text.twig';

    /**
     * @deprecated Will be removed without replacement.
     */
    public const LAYOUT_TEMPLATE_HTML = 'Mail/Mail/layout/mail_layout.html.twig';

    protected const TWIG_CONTEXT_KEY_MAIL = 'mail';

    /**
     * @var \Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface
     */
    protected $renderer;

    /**
     * @var \Spryker\Zed\Mail\Dependency\Facade\MailToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface $renderer
     * @param \Spryker\Zed\Mail\Dependency\Facade\MailToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MailToRendererInterface $renderer,
        MailToStoreFacadeInterface $storeFacade,
        MailToLocaleFacadeInterface $localeFacade
    ) {
        $this->renderer = $renderer;
        $this->storeFacade = $storeFacade;
        $this->localeFacade = $localeFacade;
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
        $localeTransfer = $this->getLocaleTransfer($mailTransfer);
        $this->renderer->setLocaleTransfer($localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(MailTransfer $mailTransfer)
    {
        $localeTransfer = $mailTransfer->getLocale();
        if (!$localeTransfer) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setLocaleName(Store::getInstance()->getCurrentLocale());
        }

        return $localeTransfer;
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
                $twigContext = $this->getTwigContext();
                $twigContext[static::TWIG_CONTEXT_KEY_MAIL] = $mailTransfer;

                $renderedContent = $this->renderer->render($templateTransfer->getName(), $twigContext);
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
                $twigContext = $this->getTwigContext();
                $twigContext[static::TWIG_CONTEXT_KEY_MAIL] = $mailTransfer;

                $renderedContent = $this->renderer->render($templateTransfer->getName(), $twigContext);
                $templateTransfer->setContent($renderedContent);
            }
        }
    }

    /**
     * @return mixed[]
     */
    protected function getTwigContext(): array
    {
        return [
            'store' => $this->storeFacade->getCurrentStore(),
            'locale' => $this->localeFacade->getCurrentLocale(),
        ];
    }
}
