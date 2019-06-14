<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface;

/**
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface getFacade()
 * @method \Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 */
class TranslationPlugin extends AbstractPlugin implements TranslationPluginInterface
{
    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        return $this->getFacade()->hasTranslation($keyName);
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    public function translate($keyName, array $data = [])
    {
        return $this->getFacade()->translate($keyName, $data);
    }
}
