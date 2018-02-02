<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Dependency\Facade;

class DatasetToTouchFacadeFacadeBridge implements DatasetToTouchFacadeInterface
{
    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Touch\Business\TouchFacadeInterface $touchFacade
     */
    public function __construct($touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $itemType
     * @param int $idDataset
     *
     * @return bool
     */
    public function touchActive($itemType, $idDataset)
    {
        return $this->touchFacade->touchActive($itemType, $idDataset);
    }

    /**
     * @param string $itemType
     * @param int $idDataset
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idDataset)
    {
        return $this->touchFacade->touchDeleted($itemType, $idDataset);
    }
}
