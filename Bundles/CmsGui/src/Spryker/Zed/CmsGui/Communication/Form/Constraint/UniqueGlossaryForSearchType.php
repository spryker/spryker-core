<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueGlossaryForSearchType extends SymfonyConstraint
{
    public const OPTION_GLOSSARY_FACADE = 'glossaryFacade';
    public const OPTION_SEARCH_TYPE = 1;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsGlossaryFacadeInterface
     */
    public function getGlossaryFacade()
    {
        return $this->glossaryFacade;
    }

    /**
     * @return int
     */
    public function getGlossarySearchTypeValidate()
    {
        return static::OPTION_SEARCH_TYPE;
    }
}
