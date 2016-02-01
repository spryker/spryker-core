<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Communication\Constraint;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
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
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @param CategoryQueryContainer $queryContainer
     * @param int $idCategory
     * @param LocaleTransfer $locale
     * @param mixed $options
     */
    public function __construct(
        CategoryQueryContainer $queryContainer,
        $idCategory,
        LocaleTransfer $locale,
        $options = null
    ) {
        parent::__construct($options);
        $this->queryContainer = $queryContainer;
        $this->idCategory = $idCategory;
        $this->locale = $locale;
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return int
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

}
