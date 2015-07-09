<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Installer;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\SearchPage\Business\Exception\TemplateAlreadyExistsException;
use SprykerFeature\Zed\SearchPage\Business\Reader\TemplateReaderInterface;
use SprykerFeature\Zed\SearchPage\Business\Writer\TemplateWriterInterface;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;

class TemplateInstaller extends AbstractInstaller
{

    const RANGE_SLIDER = 'range_slider';
    const DROP_DOWN = 'drop_down';

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    private $locator;

    /**
     * @var TemplateWriterInterface
     */
    private $templateWriter;

    /**
     * @var TemplateReaderInterface
     */
    private $templateReader;

    /**
     * @param TemplateWriterInterface $templateWriter
     * @param TemplateReaderInterface $templateReader
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        TemplateWriterInterface $templateWriter,
        TemplateReaderInterface $templateReader,
        LocatorLocatorInterface $locator
    ) {
        $this->templateWriter = $templateWriter;
        $this->templateReader = $templateReader;
        $this->locator = $locator;
    }

    /**
     */
    public function install()
    {
        if ($this->templateReader->hasTemplates()) {
            $this->info('Skipping SearchPageTemplateInstaller, cause templates are already in DB.');

            return;
        }
        $templates = $this->getTemplates();
        $this->installTemplates($templates);
    }

    /**
     * @return array
     */
    private function getTemplates()
    {
        return [
            self::RANGE_SLIDER,
            self::DROP_DOWN,
        ];
    }

    /**
     * @param array $templates
     *
     * @throws TemplateAlreadyExistsException
     */
    private function installTemplates(array $templates)
    {
        foreach ($templates as $template) {
            $hasTemplate = $this->templateReader->hasTemplateByName($template);

            if ($hasTemplate) {
                throw new TemplateAlreadyExistsException(
                    sprintf(
                        'Template %s already exists in Database',
                        $template
                    )
                );
            }

            $templateTransfer = new \Generated\Shared\Transfer\TemplateTransfer();
            $templateTransfer->setTemplateName($template);

            $this->templateWriter->createTemplate($templateTransfer);
        }
    }

}
