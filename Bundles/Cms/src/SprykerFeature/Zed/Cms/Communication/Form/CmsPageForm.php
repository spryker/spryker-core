<?php

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
    const ADD              = 'add';
    const UPDATE           = 'update';
    const ID_CMS_PAGE      = 'idCmsPage';
    const FK_TEMPLATE      = 'fkTemplate';
    const ID_URL           = 'idUrl';
    const URL              = 'url';
    const CURRENT_TEMPLATE = 'cur_temp';
    const PAGE             = 'Page';
    const IS_ACTIVE        = 'is_active';

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
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsPageQuery     $pageUrlByIdQuery
     * @param string              $formType
     * @param int                 $idPage
     * @param UrlFacade           $urlFacade
     */

    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsPageQuery $pageUrlByIdQuery, $formType, $idPage, UrlFacade $urlFacade)
    {
        $this->templateQuery    = $templateQuery;
        $this->pageUrlByIdQuery = $pageUrlByIdQuery;
        $this->formType         = $formType;
        $this->idPage           = $idPage;
        $this->urlFacade        = $urlFacade;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $urlConstraints = CmsConstraint::getMandatoryConstraints();

        if (self::ADD === $this->formType) {
            $urlConstraints[] = new Callback([
                'methods' => [
                    function ($url, ExecutionContext $context) {
                        if ($this->urlFacade->hasUrl($url)) {
                            $context->addViolation('Url is already used');
                        }
                    },
                ],
            ]);
        }

        $urlParams = [
            'label'       => 'URL',
            'constraints' => $urlConstraints,
        ];

        if (self::UPDATE === $this->formType) {
            $urlParams['disabled'] = 'disabled';
        }

        return $this->addHidden(self::ID_CMS_PAGE)
            ->addHidden(CmsQueryContainer::ID_URL)
            ->addHidden(self::CURRENT_TEMPLATE)
            ->addChoice(self::FK_TEMPLATE, [
                'label'   => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->addText(self::URL, $urlParams)
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
        if ($this->idPage) {
            $pageUrlTemplate = $this->pageUrlByIdQuery->findOne();

            return [
                self::ID_CMS_PAGE         => $pageUrlTemplate->getIdCmsPage(),
                self::FK_TEMPLATE         => $pageUrlTemplate->getFkTemplate(),
                self::URL                 => $pageUrlTemplate->getUrl(),
                self::CURRENT_TEMPLATE    => $pageUrlTemplate->getFkTemplate(),
                self::IS_ACTIVE           => $pageUrlTemplate->getIsActive(),
                CmsQueryContainer::ID_URL => $pageUrlTemplate->getIdUrl(),
            ];
        }
    }
}
