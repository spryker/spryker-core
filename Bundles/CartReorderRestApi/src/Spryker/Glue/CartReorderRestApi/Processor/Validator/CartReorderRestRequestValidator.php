<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;

class CartReorderRestRequestValidator implements CartReorderRestRequestValidatorInterface
{
    /**
     * @param list<\Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\RestCartReorderAttributesValidatorPluginInterface> $restCartReorderAttributesValidatorPlugins
     */
    public function __construct(protected array $restCartReorderAttributesValidatorPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     *
     * @return list<\Generated\Shared\Transfer\RestErrorMessageTransfer>
     */
    public function validateRestRequestAttributes(RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer): array
    {
        return $this->executeRestCartReorderAttributesValidatorPluginStack($restCartReorderRequestAttributesTransfer, []);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param list<\Generated\Shared\Transfer\RestErrorMessageTransfer> $restErrorMessageTransfers
     *
     * @return list<\Generated\Shared\Transfer\RestErrorMessageTransfer>
     */
    protected function executeRestCartReorderAttributesValidatorPluginStack(
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer,
        array $restErrorMessageTransfers
    ): array {
        foreach ($this->restCartReorderAttributesValidatorPlugins as $restCartReorderAttributesValidatorPlugin) {
            $restErrorMessageTransfers = $restCartReorderAttributesValidatorPlugin->validate(
                $restCartReorderRequestAttributesTransfer,
                $restErrorMessageTransfers,
            );
        }

        return $restErrorMessageTransfers;
    }
}
