<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use SprykerFeature\Shared\SearchPage\Dependency\TemplateInterface;
use SprykerFeature\Zed\SearchPage\Business\Exception\TemplateDoesNotExistException;
use SprykerFeature\Zed\SearchPage\Business\Reader\TemplateReaderInterface;
use Orm\Zed\SearchPage\Persistence\SpySearchPageElementTemplate;

class TemplateWriter implements TemplateWriterInterface
{

    /**
     * @var TemplateReaderInterface
     */
    private $templateReader;

    /**
     * @param TemplateReaderInterface $templateReader
     */
    public function __construct(TemplateReaderInterface $templateReader)
    {
        $this->templateReader = $templateReader;
    }

    /**
     * @param TemplateInterface $template
     *
     * @return int
     */
    public function createTemplate(TemplateInterface $template)
    {
        $templateEntity = new SpySearchPageElementTemplate();
        $templateEntity->setTemplateName($template->getTemplateName());
        $templateEntity->save();

        return $templateEntity->getPrimaryKey();
    }

    /**
     * @param TemplateInterface $template
     *
     * @throws TemplateDoesNotExistException
     *
     * @return int
     */
    public function updateTemplate(TemplateInterface $template)
    {
        $idTemplate = $template->getIdPageElementTemplate();
        $templateEntity = $this->templateReader->getTemplateById($idTemplate);

        if (is_null($templateEntity)) {
            throw new TemplateDoesNotExistException(
                sprintf(
                    'Template "%s" does not exist in the DB.',
                    $template->getTemplateName()
                )
            );
        }
        $templateEntity->setTemplateName($template->getTemplateName());
        $templateEntity->save();

        return $idTemplate;
    }

}
