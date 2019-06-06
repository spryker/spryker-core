<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form\Constraints;

use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ContentProductAbstractListConstraint extends SymfonyConstraint
{
    /**
     * @var \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface
     */
    protected $contentProductFacade;

    /**
     * @param \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface $contentProductFacade
     * @param array|null $options
     */
    public function __construct(ContentProductGuiToContentProductInterface $contentProductFacade, $options = null)
    {
        $this->contentProductFacade = $contentProductFacade;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface
     */
    public function getContentProductFacade(): ContentProductGuiToContentProductInterface
    {
        return $this->contentProductFacade;
    }
}
