<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueUrl extends SymfonyConstraint
{

    const OPTION_URL_FACADE = 'urlFacade';
    const OPTION_CMS_FACADE = 'cmsFacade';

    /**
     * @var CmsGuiToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @return CmsGuiToUrlInterface
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

}
