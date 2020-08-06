<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\User;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestUserValidator implements RestUserValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface[]
     */
    protected $restUserValidatorPlugins;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface[] $restUserValidatorPlugins
     */
    public function __construct(array $restUserValidatorPlugins)
    {
        $this->restUserValidatorPlugins = $restUserValidatorPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        foreach ($this->restUserValidatorPlugins as $restUserValidatorPlugin) {
            $restErrorMessageTransfer = $restUserValidatorPlugin->validate($restRequest);
            if ($restErrorMessageTransfer) {
                $restErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
            }
        }

        if (!$restErrorCollectionTransfer->getRestErrors()->count()) {
            return null;
        }

        return $restErrorCollectionTransfer;
    }
}
