<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;


use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsPageForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_CMS_PAGE = 'idCmsPage';
    const TEMPLATE_NAME = 'fkTemplate';
    const ID_URL = 'idUrl';
    const URL = 'url';
    const URL_TYPE = 'url_type';
    const PAGE = 'Page';
    const IS_ACTIVE = 'is_active';

    protected $templateQuery;

    protected $pageUrlQuery;

    protected $type;

    protected $idPage;

    protected $urlFacade;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     */

    public function __construct(SpyCmsTemplateQuery $templateQuery, $pageUrlQuery,$type, $idPage, $urlFacade)
    {
        $this->templateQuery = $templateQuery;
        $this->pageUrlQuery = $pageUrlQuery;
        $this->type = $type;
        $this->idPage = $idPage;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $urlConstraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 256]),
        ];

        if (self::ADD === $this->type) {
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
            'label' => 'URL',
            'constraints' => $urlConstraints,
        ];

        if (self::UPDATE == $this->type) {
            $urlParams['disabled'] = 'disabled';
        }


        return $this->addHidden(self::ID_CMS_PAGE,[
            'label'=> 'id_page'
        ])
            ->addHidden(CmsQueryContainer::ID_URL,[
                'label' => 'id_url'
            ])
            ->addChoice(self::TEMPLATE_NAME, [
            'label' => 'Template',
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
        foreach($templates->getData() as $template){
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
            $pageUrlTemplate = $this->pageUrlQuery->findOne();

            return [
                self::ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
                //            self::TEMPLATE_NAME => $pageUrlTemplate->getFkTemplate(),
                self::URL => $pageUrlTemplate->getUrl(),
                self::IS_ACTIVE => $pageUrlTemplate->getIsActive(),
                CmsQueryContainer::ID_URL => $pageUrlTemplate->getIdUrl()
            ];
        }
    }

}
