<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Plugin\Catalog;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

class ProductLabelSearchHttpFacetConfigTransferBuilderPlugin extends AbstractPlugin implements FacetConfigTransferBuilderPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'label';

    /**
     * @var string
     */
    public const PARAMETER_NAME = 'label';

    /**
     * @var string
     */
    public const STRING_FACET = 'string-facet';

    /**
     * @var string
     */
    public const FACET_TYPE_ENUMERATION = 'enumeration';

    /**
     * {@inheritDoc}
     * - Builds the product label facet filter configuration transfer for the catalog page.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build()
    {
        return (new FacetConfigTransfer())
            ->setName(static::NAME)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(static::STRING_FACET)
            ->setType(static::FACET_TYPE_ENUMERATION)
            ->setIsMultiValued(true);
    }
}
