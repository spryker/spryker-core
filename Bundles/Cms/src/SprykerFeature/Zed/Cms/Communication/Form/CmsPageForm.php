<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;


use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CmsPageForm extends AbstractForm
{

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

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     */

    public function __construct(SpyCmsTemplateQuery $templateQuery, $pageUrlQuery,$type, $idPage)
    {
        $this->templateQuery = $templateQuery;
        $this->pageUrlQuery = $pageUrlQuery;
        $this->type = $type;
        $this->idPage = $idPage;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
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
            ->addText(self::URL, [
                'label' => 'URL',
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                    new Length(['max' => 256]),
                ],
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
