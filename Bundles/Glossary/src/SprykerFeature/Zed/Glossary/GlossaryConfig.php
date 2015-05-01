<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class GlossaryConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getGlossaryKeyFileName()
    {
        return APPLICATION_ROOT_DIR . '/config/Shared/glossary_keys.php';
    }
}
