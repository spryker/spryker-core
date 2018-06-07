<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockGui;

use Codeception\Actor;
use Spryker\Zed\CmsBlockGui\CmsBlockGuiDependencyProvider;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsBlockGuiCommunicationTester extends Actor
{
    use _generated\CmsBlockGuiCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return void
     */
    public function registerCmsBlockStoreRelationFormTypePlugin()
    {
        $this->setDependency(CmsBlockGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return new StoreRelationToggleFormTypePlugin();
        });
    }
}
