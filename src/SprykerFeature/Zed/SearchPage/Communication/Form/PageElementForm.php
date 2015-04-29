<?php

namespace SprykerFeature\Zed\SearchPage\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints;

class PageElementForm extends AbstractForm
{
    /**
     * @param Request $request
     * @param LocatorLocatorInterface $locator
     * @param FactoryInterface $factory
     * @param int $idLocale
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(
        Request $request,
        LocatorLocatorInterface $locator,
        FactoryInterface $factory,
        $idLocale,
        CategoryQueryContainer $queryContainer = null
    ) {
        $this->factory = $factory;
        $this->idLocale = $idLocale;
        parent::__construct($request, $locator, $queryContainer);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($this->getCategoryIdNode())
            ->findOne()
        ;
        if (!is_null($nodeEntity)) {
            return $nodeEntity->toArray();
        }

        return [];
    }

    public function addFormFields()
    {
        $this->addField('id_search_page_element');
        $this->addField('is_element_active')
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'bool'
                ])
            ])
        ;

        $this->addField('fk_search_page_element_template')
            ->setAccepts($this->getTemplates())
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer'
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getTemplates(), 'value'),
                    'message' => 'Please choose one of the given Templates'
                ]),
                new Constraints\NotBlank()
            ])
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            })
        ;

        $this->addField('fk_search_document_attribute')
            ->setAccepts($this->getDocumentAttributes())
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer'
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getDocumentAttributes(), 'value'),
                    'message' => 'Please choose one of the given Attributes'
                ]),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            })
        ;

        $this->addTemplateField();
    }

    /**
     * @return array
     */
    protected function getCategories()
    {
        $categories = $this->queryContainer
            ->queryCategory($this->idLocale)
            ->setFormatter(new PropelArraySetFormatter())
            ->find()
        ;

        $data = [];
        foreach ($categories as $category) {
            $data[] = $this->formatOption(
                (int)$category['id_category'],
                $category['name']
            );
        }

        if (empty($data)) {
            $data[] = $this->formatOption('', '');
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getParentCategories()
    {
        $categoryNodes = $this->queryContainer
            ->queryCategoryNode($this->idLocale)
            ->setFormatter(new PropelArraySetFormatter())
            ->find()
        ;

        $data = [];
        foreach ($categoryNodes as $categoryNode) {
            $data[] = $this->formatOption(
                (int)$categoryNode[self::ID_CATEGORY_NODE],
                $categoryNode[self::CATEGORY_NAME]
            );
        }

        if (empty($data)) {
            $data[] = $this->formatOption('', '');
        }

        return $data;
    }

    /**
     * @param string $option
     * @param string $label
     * @return array
     */
    protected function formatOption($option, $label)
    {
        return [
            'value' => $option,
            'label' => $label,
        ];
    }

    /**
     * @return int
     */
    protected function getCategoryIdNode()
    {
        return $this->stateContainer->getRequestValue(self::ID_CATEGORY_NODE);
    }

    protected function addTemplateField()
    {
        $templates = $this->getTemplates();
        $constraints = [
            new Constraints\Type(['type' => 'integer']),
            new Constraints\NotBlank(),
            new Constraints\Choice([
                'choices' => array_column($templates, 'value'),
                'message' => 'Please choose one of the given Templates'
            ]),
        ];

        $this->addField('fk_search_page_element_template')
            ->setAccepts($templates)
            ->setRefresh(false)
            ->setConstraints($constraints)
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            })
        ;
    }
}
