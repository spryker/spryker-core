<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlockQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsBlockForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_CMS_PAGE = 'idCmsPage';
    const FK_TEMPLATE = 'fkTemplate';
    const BLOCK = 'block';
    const CURRENT_TEMPLATE = 'cur_temp';
    const PAGE = 'Page';
    const IS_ACTIVE = 'is_active';

    /**
     * @var SpyCmsTemplateQuery
     */
    protected $templateQuery;

    /**
     * @var SpyCmsBlockQuery
     */
    protected $blockPageByIdQuery;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsBlockQuery $blockPageByIdQuery
     * @param string $formType
     * @param int $idPage
     */
    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsBlockQuery $blockPageByIdQuery, $formType, $idPage)
    {
        $this->templateQuery = $templateQuery;
        $this->blockPageByIdQuery = $blockPageByIdQuery;
        $this->formType = $formType;
        $this->idPage = $idPage;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $blockConstraints = CmsConstraint::getMandatoryConstraints();

        if (self::ADD === $this->formType) {
            $blockConstraints[] = new Callback([
                'methods' => [
                    function ($name, ExecutionContext $context) {
                        if (!empty($this->templateQuery->useSpyCmsPageQuery()
                            ->useSpyCmsBlockQuery()
                            ->findByName($name)
                            ->getData())
                        ) {
                            $context->addViolation('Block name already exists.');
                        }
                    },
                ],
            ]);
        }

        $blockParams = [
            'label' => 'Block Name',
            'constraints' => $blockConstraints,
        ];

        if (self::UPDATE === $this->formType) {
            $blockParams['disabled'] = 'disabled';
        }

        return $this->addHidden(self::ID_CMS_PAGE)
            ->addHidden(self::CURRENT_TEMPLATE)
            ->addChoice(self::FK_TEMPLATE, [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->addText(self::BLOCK, $blockParams)
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
            $pageUrlTemplate = $this->blockPageByIdQuery->findOne();

            return [
                self::ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
                self::FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::BLOCK => $pageUrlTemplate->getName(),
                self::CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::IS_ACTIVE => (bool)$pageUrlTemplate->getIsActive(),
            ];
        }
    }
}
