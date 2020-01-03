<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Form\Constraints;

use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ContentFileListConstraint extends SymfonyConstraint
{
    /**
     * @var \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface
     */
    protected $contentFileFacade;

    /**
     * @param \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface $contentFileFacade
     * @param array|null $options
     */
    public function __construct(ContentFileGuiToContentFileFacadeInterface $contentFileFacade, ?array $options = null)
    {
        $this->contentFileFacade = $contentFileFacade;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface
     */
    public function getContentFileFacade(): ContentFileGuiToContentFileFacadeInterface
    {
        return $this->contentFileFacade;
    }
}
