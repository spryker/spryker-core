<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form;

use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsBlockForm extends AbstractForm
{

    const TYPE_STATIC = 'static';
    const CATEGORY = 'category';
    const PRODUCT = 'product';
    const SELECT_VALUE = 'selectValue';
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
     * @var string
     */
    protected $blockName;

    /**
     * @var string
     */
    protected $blockType;

    /**
     * @var string
     */
    protected $blockValue;

    /**
     * @var string
     */
    protected $selectValue;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsBlockQuery $blockPageByIdQuery
     * @param string $formType
     * @param int $idCmsBlock
     */
    public function __construct(
        SpyCmsTemplateQuery $templateQuery,
        SpyCmsBlockQuery $blockPageByIdQuery,
        $formType,
        $idCmsBlock
    ) {
        $this->templateQuery = $templateQuery;
        $this->blockPageByIdQuery = $blockPageByIdQuery;
        $this->formType = $formType;
        $this->idCmsBlock = $idCmsBlock;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $blockConstraints = $this->getConstraints()->getMandatoryConstraints();

        $blockConstraints[] = $this->getConstraints()->createConstraintCallback([
            'methods' => [
                function ($name, ExecutionContext $context) {
                    $formData = $context->getRoot()->getViewData();
                    if (!empty($this->checkExistingBlock($name, $formData)) && ($this->blockName !== $name
                        || $this->blockType !== $formData['type']
                        || $this->blockValue !== intval($formData['value']))
                    ) {
                        $context->addViolation('Block name with same Type and Value already exists.');
                    }
                },
            ],
        ]);

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
                    self::TYPE_STATIC => 'Static',
                    self::CATEGORY => 'Category',
                    self::PRODUCT => 'Product'
                ],
            ])
            ->addText(self::SELECT_VALUE, [
                'label' => 'Value',
            ])
            ->addHidden(self::VALUE, [
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
            $this->blockType = $pageUrlTemplate->getType();
            $this->blockValue = $pageUrlTemplate->getValue();
            $this->selectValue = $pageUrlTemplate->getCategoryName();

            return [
                self::ID_CMS_BLOCK => $pageUrlTemplate->getIdCmsBlock(),
                self::FK_PAGE => $pageUrlTemplate->getFkPage(),
                self::FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::NAME => $pageUrlTemplate->getName(),
                self::TYPE => $pageUrlTemplate->getType(),
                self::SELECT_VALUE => $pageUrlTemplate->getCategoryName(),
                self::VALUE => $pageUrlTemplate->getValue(),
                self::CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::IS_ACTIVE => (bool)$pageUrlTemplate->getIsActive(),
            ];
        }
    }

    /**
     * @param $name
     * @param $this
     * @param $formData
     *
     * @return array
     */
    private function checkExistingBlock($name, $formData)
    {
        return $this->templateQuery->useSpyCmsPageQuery()
            ->useSpyCmsBlockQuery()
            ->filterByName($name)
            ->filterByType($formData['type'])
            ->filterByValue($formData['value'])
            ->find()
            ->getData()
            ;
    }
}
