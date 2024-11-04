<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SymfonyMailer\Business\Renderer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Spryker\Zed\SymfonyMailer\Business\Renderer\TwigRenderer;
use Spryker\Zed\SymfonyMailer\Dependency\Renderer\SymfonyMailerToRendererBridge;
use Spryker\Zed\SymfonyMailer\Dependency\Renderer\SymfonyMailerToRendererInterface;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SymfonyMailer
 * @group Business
 * @group Renderer
 * @group TwigRendererTest
 * Add your own group annotations below this line
 */
class TwigRendererTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SymfonyMailer\SymfonyMailerBusinessTester
     */
    public $tester;

    /**
     * @var string
     */
    protected const LAYOUT_TEMPLATE_TEXT = 'SymfonyMailer/SymfonyMailer/layout/mail_layout.text.twig';

    /**
     * @var string
     */
    protected const LAYOUT_TEMPLATE_HTML = 'SymfonyMailer/SymfonyMailer/layout/mail_layout.html.twig';

    /**
     * @var int
     */
    protected const INDEX_OF_TEMPLATE_TEXT = 0;

    /**
     * @var int
     */
    protected const INDEX_OF_TEMPLATE_HTML = 1;

    /**
     * @var string
     */
    protected const FAKE_HTML_TEMPLATE = 'HtmlTemplate';

    /**
     * @var string
     */
    protected const FAKE_TEXT_TEMPLATE = 'TextTemplate';

    /**
     * @var string
     */
    protected const FAKE_SUBJECT_KEY = 'fake.subject.key';

    /**
     * @var string
     */
    protected const LOCALE = 'en_US';

    /**
     * @return void
     */
    public function testHydrateMailCallsTwigsRenderMethodWithTextTemplate(): void
    {
        //Arrange
        $mailTransfer = $this->getMailTransfer();
        $twigRenderer = new TwigRenderer(
            $this->getTwigEnvironmentMock(),
            $this->tester->getLocaleFacade(),
        );

        //Act
        $twigRenderer->render($mailTransfer);

        //Assert
        $mailTemplateTextTransfer = $mailTransfer->getTemplates()[static::INDEX_OF_TEMPLATE_TEXT];
        $this->assertSame(static::FAKE_TEXT_TEMPLATE, $mailTemplateTextTransfer->getContent());
    }

    /**
     * @return void
     */
    public function testHydrateMailCallsTwigsRenderMethodWithHtmlTemplate(): void
    {
        //Arrange
        $mailTransfer = $this->getMailTransfer();
        $twigRenderer = new TwigRenderer(
            $this->getTwigEnvironmentMock(),
            $this->tester->getLocaleFacade(),
        );

        //Act
        $twigRenderer->render($mailTransfer);

        //Assert
        $mailTemplateHtmlTransfer = $mailTransfer->getTemplates()[static::INDEX_OF_TEMPLATE_HTML];
        $this->assertSame(static::FAKE_HTML_TEMPLATE, $mailTemplateHtmlTransfer->getContent());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SymfonyMailer\Dependency\Renderer\SymfonyMailerToRendererInterface
     */
    protected function getTwigEnvironmentMock(): SymfonyMailerToRendererInterface
    {
        $twigEnvironmentMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['render', 'getExtension'])
            ->getMock();
        $twigEnvironmentMock->expects($this->once())
            ->method('getExtension')
            ->with(TwigTranslatorPlugin::class)
            ->willReturn(new TwigTranslatorPlugin());

        $renderCallCount = 0;
        $twigEnvironmentMock
            ->expects($this->exactly(2))
            ->method('render')
            ->with($this->callback(function ($template) use (&$renderCallCount) {
                $expectedTemplates = [static::LAYOUT_TEMPLATE_TEXT, static::LAYOUT_TEMPLATE_HTML];

                return $template === $expectedTemplates[$renderCallCount++];
            }))
            ->willReturnCallback(function () use (&$renderCallCount) {
                $returnValues = [static::FAKE_TEXT_TEMPLATE, static::FAKE_HTML_TEMPLATE];

                return $returnValues[$renderCallCount - 1];
            });

        return new SymfonyMailerToRendererBridge($twigEnvironmentMock);
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer(): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->addTemplate($this->getMailTemplateTransferText());
        $mailTransfer->addTemplate($this->getMailTemplateTransferHtml());
        $mailTransfer->setSubject(static::FAKE_SUBJECT_KEY);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(static::LOCALE);
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
