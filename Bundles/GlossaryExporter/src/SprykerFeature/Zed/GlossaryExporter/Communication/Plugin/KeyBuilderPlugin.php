<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\GlossaryExporter\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Glossary\Code\KeyBuilder\GlossaryKeyBuilder;

class KeyBuilderPlugin extends AbstractPlugin implements KeyBuilderInterface
{

    use GlossaryKeyBuilder;

}
