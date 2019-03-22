<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;

/**
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
abstract class AbstractTwigExtensionPlugin extends AbstractPlugin implements ExtensionInterface
{
    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @api
     *
     * @param \Twig\Environment $environment The current Environment instance
     *
     * @return void
     */
    public function initRuntime(Environment $environment)
    {
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @api
     *
     * @return array An array of TokenParserInterface or TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return [];
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @api
     *
     * @return \Twig\NodeVisitor\NodeVisitorInterface[] An array of NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return [];
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @api
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @api
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return [];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @api
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [];
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @api
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return [];
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @api
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [];
    }
}
