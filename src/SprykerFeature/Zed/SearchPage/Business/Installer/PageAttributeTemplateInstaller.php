<?php

namespace SprykerFeature\SearchPage\Business\Installer;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\SearchPage\Business\Exception\TemplateAlreadyExistsException;
use SprykerFeature\SearchPage\Business\Reader\PageAttributeTemplateReaderInterface;
use SprykerFeature\SearchPage\Business\Writer\PageAttributeTemplateWriterInterface;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;

class PageAttributeTemplateInstaller extends AbstractInstaller
{
    const RANGE_SLIDER = 'range_slider';
    const DROP_DOWN = 'drop_down';

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    private $locator;

    /**
     * @var PageAttributeTemplateWriterInterface
     */
    private $templateWriter;

    /**
     * @var PageAttributeTemplateReaderInterface
     */
    private $templateReader;

    /**
     * @param PageAttributeTemplateWriterInterface $templateWriter
     * @param PageAttributeTemplateReaderInterface $templateReader
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        PageAttributeTemplateWriterInterface $templateWriter,
        PageAttributeTemplateReaderInterface $templateReader,
        LocatorLocatorInterface $locator
    ) {
        $this->templateWriter = $templateWriter;
        $this->templateReader = $templateReader;
        $this->locator = $locator;
    }

    /**
     * @return void
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
            self::DROP_DOWN
        ];
    }

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
//            $templateTransfer = $this->locator->searchPage()->transferDocumentAttribute()
//            $this->templateWriter->createPageAttributeTemplate();
        }
    }
}
