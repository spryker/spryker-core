<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsPageForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_CMS_PAGE = 'idCmsPage';
    const FK_TEMPLATE = 'fkTemplate';
    const ID_URL = 'idUrl';
    const URL = 'url';
    const CURRENT_TEMPLATE = 'cur_temp';
    const PAGE = 'Page';
    const IS_ACTIVE = 'is_active';

    /**
     * @var SpyCmsTemplateQuery
     */
    protected $templateQuery;

    /**
     * @var SpyCmsPageQuery
     */
    protected $pageUrlByIdQuery;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var string
     */
    protected $pageUrl;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsPageQuery $pageUrlByIdQuery
     * @param CmsToUrlInterface $urlFacade
     * @param string $formType
     * @param int $idPage
     */

    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsPageQuery $pageUrlByIdQuery, CmsToUrlInterface $urlFacade, $formType, $idPage)
    {
        $this->templateQuery = $templateQuery;
        $this->pageUrlByIdQuery = $pageUrlByIdQuery;
        $this->formType = $formType;
        $this->idPage = $idPage;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $urlConstraints = $this->getConstraints()->getMandatoryConstraints();

        $urlConstraints[] = $this->getConstraints()->createConstraintCallback([
            'methods' => [
                function ($url, ExecutionContext $context) {
                    if ($this->urlFacade->hasUrl($url) && $this->pageUrl !== $url) {
                        $context->addViolation('Url is already used');
                    }
                },
            ],
        ]);

        return $this->addHidden(self::ID_CMS_PAGE)
            ->addHidden(CmsQueryContainer::ID_URL)
            ->addHidden(self::CURRENT_TEMPLATE)
            ->addChoice(self::FK_TEMPLATE, [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->addText(self::URL, [
                'label' => 'URL',
                'constraints' => $urlConstraints,
            ])
            ->addCheckbox(self::IS_ACTIVE, [
                'label' => 'Active',
            ]);
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templates = $this->templateQuery->find();

        $result = [];
        foreach ($templates->getData() as $template) {
            $result[$template->getIdCmsTemplate()] = $template->getTemplateName();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        if ($this->idPage !== null) {
            $pageUrlTemplate = $this->pageUrlByIdQuery->findOne();

            $this->pageUrl = $pageUrlTemplate->getUrl();

            return [
                self::ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
                self::FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::URL => $pageUrlTemplate->getUrl(),
                self::CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::IS_ACTIVE => $pageUrlTemplate->getIsActive(),
                CmsQueryContainer::ID_URL => $pageUrlTemplate->getIdUrl(),
            ];
        }
    }

}
