<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Dependency\Plugin;

interface FormPluginInterface
{

    /**
     * @param array $output
     *
     * @return mixed
     */
    public function extendOutput(array $output);

    /**
     * @return bool
     */
    public function isValid();

}
