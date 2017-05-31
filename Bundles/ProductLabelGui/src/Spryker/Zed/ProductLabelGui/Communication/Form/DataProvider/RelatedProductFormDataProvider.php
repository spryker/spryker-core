<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductLabelAbstractProductRelationsTransfer;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;

class RelatedProductFormDataProvider
{

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    public function __construct(ProductLabelFacadeInterface $productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelAbstractProductRelationsTransfer
     */
    public function getData($idProductLabel = null)
    {
        $relationsTransfer = new ProductLabelAbstractProductRelationsTransfer();
        $relationsTransfer->setIdProductLabel($idProductLabel);

        return $relationsTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
