<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Form;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Base\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\Base\SpyCategoryNode;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryForm extends AbstractForm
{

    const NAME = 'name';
    const PK_CATEGORY = 'id_product_category';
    const FK_CATEGORY_NODE = 'fk_category_node';
    const FK_PARENT_CATEGORY_NODE = 'fk_parent_category_node';
    const SUBMIT = 'submit';

    /**
     * @var SpyCategoryQuery
     */
    protected $categoryQuery;

    /**
     * @var CategoryQueryContainer
     */
    protected $categoryQueryContainer;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @param SpyCategoryQuery $categoryQuery
     */
    public function __construct(SpyCategoryQuery $categoryQuery, CategoryQueryContainer $categoryQueryContainer, LocaleTransfer $locale)
    {
        $this->categoryQuery = $categoryQuery;
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->locale = $locale;
    }

    /**
     * @return CategoryForm
     */
    protected function buildFormFields()
    {
        return $this->addText(self::NAME, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
            ->addChoice(self::FK_PARENT_CATEGORY_NODE, [
                'label' => 'Parent',
                'placeholder' => '-select-',
                'choices' => $this->getCategories(),
                'data' => 1
            ])
            ;
    }

    /**
     * @return array
     */
    protected function getCategories()
    {
        $categories = $this->categoryQueryContainer
            ->queryCategory($this->locale->getIdLocale())
            ->setFormatter(new PropelArraySetFormatter())
            ->find()
        ;

        $data = [];
        foreach ($categories as $category) {
            $data[] = $this->formatOption(
                (int) $category['id_category'],
                $category['name']
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
     *
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
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [
            self::FK_CATEGORY_NODE => null,
            self::NAME => '',
        ];

        /**
         * @var SpyCategory $category
         */
        $category = $this->categoryQuery->innerJoinAttribute()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->findOne()
        ;

        if ($category) {
            $category = $category->toArray();
            die(dump($category));
            $result = [
                self::PK_CATEGORY => $category[self::PK_CATEGORY],
                self::FK_CATEGORY_NODE => $category[self::FK_CATEGORY_NODE],
                self::NAME => $category[self::NAME]
            ];
        }

        return $result;
    }

}
