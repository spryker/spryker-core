<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Renderer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Spryker\Zed\Mail\Business\Model\Renderer\TwigRenderer;
use Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface;
use Spryker\Zed\Mail\Dependency\Facade\MailToStoreFacadeInterface;
use Spryker\Zed\Mail\Dependency\Renderer\MailToRendererBridge;
use Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface;
use Spryker\Zed\Mail\MailDependencyProvider;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Renderer
 * @group TwigRendererTest
 * Add your own group annotations below this line
 */
class TwigRendererTest extends Unit
{
    public const INDEX_OF_TEMPLATE_TEXT = 0;
    public const INDEX_OF_TEMPLATE_HTML = 1;

    /**
     * @var \SprykerTest\Zed\Mail\MailBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testHydrateMailCallsTwigsRenderMethodWithTextTemplate(): void
    {
        $mailTransfer = $this->getMailTransfer();
        $twigRenderer = new TwigRenderer(
            $this->getTwigEnvironmentMock(),
            $this->getMailToStoreFacadeMock(),
            $this->getMailToLocaleFacadeMock()
        );
        $twigRenderer->render($mailTransfer);

        $mailTemplateTextTransfer = $mailTransfer->getTemplates()[static::INDEX_OF_TEMPLATE_TEXT];
        $this->assertSame('TextTemplate', $mailTemplateTextTransfer->getContent());
    }

    /**
     * @return void
     */
    public function testHydrateMailCallsTwigsRenderMethodWithHtmlTemplate(): void
    {
        $mailTransfer = $this->getMailTransfer();
        $twigRenderer = new TwigRenderer(
            $this->getTwigEnvironmentMock(),
            $this->getMailToStoreFacadeMock(),
            $this->getMailToLocaleFacadeMock()
        );
        $twigRenderer->render($mailTransfer);

        $mailTemplateHtmlTransfer = $mailTransfer->getTemplates()[static::INDEX_OF_TEMPLATE_HTML];
        $this->assertSame('HtmlTemplate', $mailTemplateHtmlTransfer->getContent());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface
     */
    protected function getTwigEnvironmentMock(): MailToRendererInterface
    {
        $twigEnvironmentMock = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->setMethods(['render', 'getExtension'])->getMock();
        $twigEnvironmentMock->expects($this->at(0))->method('getExtension')->with('translator')->willReturn(new TwigTranslatorPlugin());
        $twigEnvironmentMock
            ->expects($this->at(1))
            ->method('render')
            ->willReturn('TextTemplate');

        $twigEnvironmentMock
            ->expects($this->at(2))
            ->method('render')
            ->willReturn('HtmlTemplate');

        $rendererBridge = new MailToRendererBridge($twigEnvironmentMock);

        return $rendererBridge;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Facade\MailToStoreFacadeInterface
     */
    protected function getMailToStoreFacadeMock(): MailToStoreFacadeInterface
    {
        $mailToStoreFacadeMock = $this->getMockBuilder(MailToStoreFacadeInterface::class)->getMock();
        $this->tester->setDependency(MailDependencyProvider::FACADE_STORE, $mailToStoreFacadeMock);

        return $mailToStoreFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface
     */
    protected function getMailToLocaleFacadeMock(): MailToLocaleFacadeInterface
    {
        $mailToLocaleFacadeMock = $this->getMockBuilder(MailToLocaleFacadeInterface::class)->getMock();
        $this->tester->setDependency(MailDependencyProvider::FACADE_LOCALE, $mailToLocaleFacadeMock);

        return $mailToLocaleFacadeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer(): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->addTemplate($this->getMailTemplateTransferText());
        $mailTransfer->addTemplate($this->getMailTemplateTransferHtml());

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName('en_US');
        $mailTransfer->setLocale($localeTransfer);

        return $mailTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTemplateTransfer
     */
    protected function getMailTemplateTransferText(): MailTemplateTransfer
    {
        return $this->getMailTemplateTransfer(false);
    }

    /**
     * @return \Generated\Shared\Transfer\MailTemplateTransfer
     */
    protected function getMailTemplateTransferHtml(): MailTemplateTransfer
    {
        return $this->getMailTemplateTransfer(true);
    }

    /**
     * @param bool $isHtml
     *
     * @return \Generated\Shared\Transfer\MailTemplateTransfer
     */
    protected function getMailTemplateTransfer(bool $isHtml): MailTemplateTransfer
    {
        $mailTemplateTransfer = new MailTemplateTransfer();
        $mailTemplateTransfer->setIsHtml($isHtml);

        return $mailTemplateTransfer;
    }
}
