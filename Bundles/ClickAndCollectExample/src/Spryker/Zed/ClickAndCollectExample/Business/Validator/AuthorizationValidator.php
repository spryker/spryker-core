<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToMerchantUserFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationValidator implements AuthorizationValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_USER_UNAUTHORIZED_REQUEST = 'Merchant user is not allowed to access the resource.';

    /**
     * @var string
     */
    protected const ERROR_RESPONSE_CODE_MERCHANT_USER_UNAUTHORIZED_REQUEST = '5700';

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToMerchantUserFacadeInterface
     */
    protected ClickAndCollectExampleToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        ClickAndCollectExampleToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        if (!$glueRequestTransfer->getRequestUser() || !$glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifier()) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $merchantUserTransfer = $this->merchantUserFacade->findMerchantUser(
            (new MerchantUserCriteriaTransfer())->setIdUser($glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifierOrFail()),
        );

        if (!$merchantUserTransfer) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setMessage(static::ERROR_MESSAGE_MERCHANT_USER_UNAUTHORIZED_REQUEST)
            ->setCode(static::ERROR_RESPONSE_CODE_MERCHANT_USER_UNAUTHORIZED_REQUEST);

        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setValidationError(static::ERROR_MESSAGE_MERCHANT_USER_UNAUTHORIZED_REQUEST)
            ->addError($glueErrorTransfer)
            ->setStatus(Response::HTTP_FORBIDDEN);
    }
}
