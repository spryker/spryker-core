<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Graph\Communication\Exception;

abstract class AbstractGraphAdapterException extends \Exception
{

    const MESSAGE = 'Please check the return value of your GraphConfig::getGraphAdapterName(). This should be something like "GraphAdapter::class"';

}
