<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Shared\Transfer\TransferInterface;
use Symfony\Component\Form\FormBuilderInterface;
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
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_block';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $blockConstraints = $this->getConstraints()->getMandatoryConstraints();

        $blockConstraints[] = $this->getConstraints()->createConstraintCallback([
            'methods' => [
                function ($name, ExecutionContext $context) {
                    $formData = $context->getRoot()->getViewData();
                    if (!empty($this->checkExistingBlock($name, $formData)) && ($this->blockName !== $name
                        || $this->blockType !== $formData['type']
                        || $this->blockValue !== (int) $formData['value'])
                    ) {
                        $context->addViolation('Block name with same Type and Value already exists.');
                    }
                },
            ],
        ]);

        $builder->add(self::ID_CMS_BLOCK, 'hidden')
            ->add(self::CURRENT_TEMPLATE, 'hidden')
            ->add(self::FK_PAGE, 'hidden')
            ->add(self::FK_TEMPLATE, 'choice', [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->add(self::NAME, 'text', [
                'label' => 'Name',
                'constraints' => $blockConstraints,
            ])
            ->add(self::TYPE, 'choice', [
                'label' => 'Type',
                'choices' => [
                    self::TYPE_STATIC => 'Static',
                    self::CATEGORY => 'Category',
                    self::PRODUCT => 'Product',
                ],
            ])
            ->add(self::SELECT_VALUE, 'text', [
                'label' => 'Value',
            ])
            ->add(self::VALUE, 'hidden', [
                'label' => 'Value',
            ])
            ->add(self::IS_ACTIVE, 'checkbox', [
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
    public function populateFormFields()
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
                self::IS_ACTIVE => (bool) $pageUrlTemplate->getIsActive(),
            ];
        }
    }

    /**
     * @param string $name
     * @param array $formData
     *
     * @return array
     */
    private function checkExistingBlock($name, array $formData)
    {
        return $this->templateQuery->useSpyCmsPageQuery()
            ->useSpyCmsBlockQuery()
            ->filterByName($name)
            ->filterByType($formData['type'])
            ->filterByValue($formData['value'])
            ->find()
            ->getData();
    }

}
