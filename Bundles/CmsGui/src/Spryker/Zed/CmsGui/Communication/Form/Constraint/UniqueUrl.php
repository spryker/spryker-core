<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueUrl extends SymfonyConstraint
{
    public const OPTION_URL_FACADE = 'urlFacade';
    public const OPTION_CMS_FACADE = 'cmsFacade';

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->urlFacade;
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    public function getCmsFacade()
    {
        return $this->cmsFacade;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
