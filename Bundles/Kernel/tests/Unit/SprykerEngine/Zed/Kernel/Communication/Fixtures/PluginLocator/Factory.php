<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator;

use SprykerEngine\Zed\Kernel\Communication\Factory as CommunicationFactory;

class Factory extends CommunicationFactory
{

    /**
     * @var string
     */
    protected $classNamePattern =
        '\\Unit\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\PluginLocator\\'
    ;

}
