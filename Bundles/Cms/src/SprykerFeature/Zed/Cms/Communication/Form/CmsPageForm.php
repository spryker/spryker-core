<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use Symfony\Component\Validator\Constraints\Callback;
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
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var CmsConstraint
     */
    protected $constraints;

    /**
     * @var string
     */
    protected $pageUrl;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsPageQuery $pageUrlByIdQuery
     * @param UrlFacade $urlFacade
     * @param CmsConstraint $constraints
     * @param string $formType
     * @param int $idPage
     */

    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsPageQuery $pageUrlByIdQuery, UrlFacade $urlFacade, CmsConstraint $constraints, $formType, $idPage)
    {
        $this->templateQuery = $templateQuery;
        $this->pageUrlByIdQuery = $pageUrlByIdQuery;
        $this->constraints = $constraints;
        $this->formType = $formType;
        $this->idPage = $idPage;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $urlConstraints = $this->constraints->getMandatoryConstraints();

        $urlConstraints[] = new Callback([
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
            ])
            ;
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
        if (null !== $this->idPage) {
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
