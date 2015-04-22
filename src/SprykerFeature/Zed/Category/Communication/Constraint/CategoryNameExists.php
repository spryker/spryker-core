<?php

namespace SprykerFeature\Zed\Category\Communication\Constraint;

use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\Validator\Constraint;

class CategoryNameExists extends Constraint
{

    public $message = 'A category with the name {{ value }} already exists in the Database!';

    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @param CategoryQueryContainer $queryContainer
     * @param $idCategory
     * @param $idLocale
     * @param null $options
     */
    public function __construct(
        CategoryQueryContainer $queryContainer,
        $idCategory,
        $idLocale,
        $options = null
    ) {
        $this->queryContainer= $queryContainer;
        $this->idCategory = $idCategory;
        $this->idLocale = $idLocale;
        parent::__construct($options);
    }

    /**
     * @return CategoryQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @return string
     */
    public function getIdLocale()
    {
        return $this->idLocale;
    }

    /**
     * @return int
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }
}
