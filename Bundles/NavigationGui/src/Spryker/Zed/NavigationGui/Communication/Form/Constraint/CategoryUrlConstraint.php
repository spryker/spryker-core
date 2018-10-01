<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class CategoryUrlConstraint extends SymfonyConstraint
{
    public const OPTION_URL_FACADE = 'urlFacade';

    /**
     * @var \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToUrlInterface
     */
    protected $urlFacade;

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $value
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrl(NavigationNodeLocalizedAttributesTransfer $value)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($value->getCategoryUrl());

        return $this->urlFacade->findUrl($urlTransfer);
    }
}
