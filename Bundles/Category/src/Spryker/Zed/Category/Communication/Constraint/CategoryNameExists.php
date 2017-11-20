<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Constraint;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @deprecated Will be removed with next major release
 */
class CategoryNameExists extends Constraint
{
    /**
     * @var string
     */
    public $message = 'A category with the name {{ value }} already exists in the Database!';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param mixed $options
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
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
