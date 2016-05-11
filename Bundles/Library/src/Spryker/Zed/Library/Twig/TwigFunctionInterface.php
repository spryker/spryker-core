<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Twig;

interface TwigFunctionInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return callable
     */
    public function getCallable();

    /**
     * @return string
     */
    public function getNodeClass();

    /**
     * @param array $arguments
     */
    public function setArguments($arguments);

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return bool
     */
    public function needsEnvironment();

    /**
     * @return bool
     */
    public function needsContext();

    /**
     * @param \Twig_Node $functionArgs
     *
     * @return array
     */
    public function getSafe(\Twig_Node $functionArgs);

    /**
     * @return bool
     */
    public function isVariadic();

}