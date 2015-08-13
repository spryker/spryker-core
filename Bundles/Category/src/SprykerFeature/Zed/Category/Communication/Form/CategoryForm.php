<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Form;

use SprykerFeature\Zed\Category\Persistence\Propel\Base\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryForm extends AbstractForm
{

    const NAME = 'name';
    const CATEGORY = 'id_category';
    const SUBMIT = 'submit';

    /**
     * @var SpyCategoryQuery
     */
    protected $categoryQuery;

    /**
     * @param SpyCategoryQuery $categoryQuery
     */
    public function __construct(SpyCategoryQuery $categoryQuery)
    {
        $this->categoryQuery = $categoryQuery;
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
            ->addChoice(self::CATEGORY, [
                'label' => 'Parent',
                'placeholder' => '-select-',
                'choices' => $this->getTreeForSelect(),
            ])
            ;
    }

    /**
     * @return array
     */
    protected function getTreeForSelect()
    {
        return [
            SpyCustomerTableMap::COL_SALUTATION_MR => SpyCustomerTableMap::COL_SALUTATION_MR,
            SpyCustomerTableMap::COL_SALUTATION_MRS => SpyCustomerTableMap::COL_SALUTATION_MRS,
            SpyCustomerTableMap::COL_SALUTATION_DR => SpyCustomerTableMap::COL_SALUTATION_DR,
        ];
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [
            self::CATEGORY => null,
            self::NAME => '',
        ];

        /**
         * @var SpyCategory $category
         */
        $category = $this->categoryQuery->innerJoinAttribute()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name') //does ze join
            ->findOne()
        ;

        //dump($category->toArray());
        if ($category) {
            $category = $category->toArray();
            $result = [
                self::CATEGORY => $category[self::CATEGORY],
                self::NAME => $category[self::NAME]
            ];
        }

        return $result;
    }

}
