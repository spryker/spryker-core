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
    const PK_CATEGORY = 'id_category';
    const PK_CATEGORY_NODE = 'id_category_node';
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
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
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
            $data[$category['id_category']] = $category['name'];
        }

        return $data;
    }


    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [
            self::PK_CATEGORY => null,
            self::PK_CATEGORY_NODE => null,
            self::FK_PARENT_CATEGORY_NODE => null,
            self::NAME => '',
        ];

        /**
         * @var SpyCategory $category
         */
        $category = $this->categoryQuery->innerJoinAttribute()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::NAME)
            ->innerJoinNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, self::FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, self::PK_CATEGORY_NODE)
            ->findOne()
        ;

        if ($category) {
            $category = $category->toArray();
            
            $result = [
                self::PK_CATEGORY => $category[self::PK_CATEGORY],
                self::PK_CATEGORY_NODE => $category[self::PK_CATEGORY_NODE],
                self::FK_PARENT_CATEGORY_NODE => $category[self::FK_PARENT_CATEGORY_NODE],
                self::NAME => $category[self::NAME]
            ];
        }

        return $result;
    }

}
