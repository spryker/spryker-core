<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlockQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsBlockForm extends AbstractForm
{

    const CATEGORY = 'category';
    const PRODUCT = 'product';
    const ID_CMS_BLOCK = 'idCmsBlock';
    const FK_PAGE = 'fkPage';
    const FK_TEMPLATE = 'fkTemplate';
    const NAME = 'name';
    const TYPE = 'type';
    const VALUE = 'value';
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
    protected $idCmsBlock;

    /**
     * @var CmsConstraint
     */
    protected $constraints;

    /**
     * @var string
     */
    protected $blockName;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsBlockQuery $blockPageByIdQuery
     * @param CmsConstraint $constraints
     * @param string $formType
     * @param int $idPage
     */
    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsBlockQuery $blockPageByIdQuery, CmsConstraint $constraints, $formType, $idCmsBlock)
    {
        $this->templateQuery = $templateQuery;
        $this->blockPageByIdQuery = $blockPageByIdQuery;
        $this->constraints = $constraints;
        $this->formType = $formType;
        $this->idCmsBlock = $idCmsBlock;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $blockConstraints = $this->constraints->getMandatoryConstraints();


//            $blockConstraints[] = new Callback([
//                'methods' => [
//                    function ($name, ExecutionContext $context) {
//                        if (!empty($this->templateQuery->useSpyCmsPageQuery()
//                            ->useSpyCmsBlockQuery()
//                            ->findByName($name)
//                            ->getData() && $this->blockName !== $name)
//                        ) {
//                            $context->addViolation('Block name already exists.');
//                        }
//                    },
//                ],
//            ]);

        return $this->addHidden(self::ID_CMS_BLOCK)
            ->addHidden(self::CURRENT_TEMPLATE)
            ->addHidden(self::FK_PAGE)
            ->addChoice(self::FK_TEMPLATE, [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->addText(self::NAME, [
                'label' => 'Name',
                'constraints' => $blockConstraints,
            ])
            ->addChoice(self::TYPE, [
                'label' => 'Type',
                'choices' => [
                    'none' => '-- select --',
                    self::CATEGORY => 'Category',
                    self::PRODUCT => 'Product'
                ],
            ])
            ->addText(self::VALUE, [
                'label' => 'Value',
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
        if ($this->idCmsBlock) {
            $pageUrlTemplate = $this->blockPageByIdQuery->findOne();
            $this->blockName = $pageUrlTemplate->getName();

            return [
                self::ID_CMS_BLOCK => $pageUrlTemplate->getIdCmsBlock(),
                self::FK_PAGE => $pageUrlTemplate->getFkPage(),
                self::FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::NAME => $pageUrlTemplate->getName(),
                self::TYPE => $pageUrlTemplate->getType(),
                self::VALUE => $pageUrlTemplate->getValue(),
                self::CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::IS_ACTIVE => (bool)$pageUrlTemplate->getIsActive(),
            ];
        }
    }
}
