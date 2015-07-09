<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\GlossaryExporter\Communication\Plugin;

use SprykerFeature\Shared\Glossary\Code\KeyBuilder\GlossaryKeyBuilder;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class KeyBuilderPlugin extends AbstractPlugin implements KeyBuilderInterface
{

    use GlossaryKeyBuilder;

}
