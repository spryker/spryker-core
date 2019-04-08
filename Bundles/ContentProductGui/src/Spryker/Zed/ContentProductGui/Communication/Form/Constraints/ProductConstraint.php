<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form\Constraints;

use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ProductConstraint extends SymfonyConstraint
{
    public const CONTENT_PRODUCT_FACADE = 'contentProductFacade';

    protected const MESSAGE = 'Product quantity exceeded.';

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
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface
     */
    public function getContentProductFacade()
    {
        return $this->contentProductFacade;
    }
}
