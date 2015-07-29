<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Base\SpyCmsPageQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CmsForm extends AbstractForm
{

    const TEMPLATE_NAME = 'fkTemplate';
    const URL = 'url';
    const URL_TYPE = 'url_type';
    const PAGE = 'Page';
    const REDIRECT = 'Redirect';
    const IS_ACTIVE = 'is_active';

    protected $templateQuery;
    protected $type;

//    /**
//     * @param SpyCmsPageQuery $pageQuery
//     */
//    public function __construct(SpyCmsPageQuery $pageQuery)
//    {
//        $this->pageQuery = $pageQuery;
//    }

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     */

    public function __construct(SpyCmsTemplateQuery $templateQuery,$type)
    {
        $this->templateQuery = $templateQuery;
        $this->type = $type;
    }

    /**
     * @return CmsForm
     */
    protected function buildFormFields()
    {
        return $this->addChoice(self::TEMPLATE_NAME, [
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
            ->addChoice(self::URL_TYPE,[
                'label' => 'URL Type',
                'choices' => $this->getUrlTypes(),
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

    public function getUrlTypes(){
        return [
            self::PAGE,
            self::REDIRECT,
        ];
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
//        $order = $this->orderQuery->findOne();
//
//        return [
//            self::FIRST_NAME => $order->getFirstName(),
//            self::LAST_NAME => $order->getLastName(),
//            self::SALUTATION => $order->getSalutation(),
//            self::EMAIL => $order->getEmail(),
//        ];
    }

}
