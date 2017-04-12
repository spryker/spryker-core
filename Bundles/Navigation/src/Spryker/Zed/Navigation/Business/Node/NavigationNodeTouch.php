<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface;

class NavigationNodeTouch implements NavigationNodeTouchInterface
{

    /**
     * @var \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface
     */
    protected $navigationTouch;

    /**
     * @param \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface $navigationTouch
     */
    public function __construct(NavigationTouchInterface $navigationTouch)
    {
        $this->navigationTouch = $navigationTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return bool
     */
    public function touchNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeTransfer->requireFkNavigation();

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($navigationNodeTransfer->getFkNavigation());

        return $this->navigationTouch->touchActive($navigationTransfer);
    }

}
