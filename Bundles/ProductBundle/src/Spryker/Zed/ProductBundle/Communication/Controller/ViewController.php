<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 */
class ViewController extends BaseOptionController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {

    }

}
