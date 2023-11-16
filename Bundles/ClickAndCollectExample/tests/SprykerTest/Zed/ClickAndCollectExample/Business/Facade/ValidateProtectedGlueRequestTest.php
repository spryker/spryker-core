<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ClickAndCollectExample\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ClickAndCollectExample
 * @group Business
 * @group Facade
 * @group ValidateProtectedGlueRequestTest
 * Add your own group annotations below this line
 */
class ValidateProtectedGlueRequestTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @uses \Spryker\Zed\ClickAndCollectExample\Business\Validator\AuthorizationValidator::ERROR_MESSAGE_MERCHANT_USER_UNAUTHORIZED_REQUEST
     *
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_USER_UNAUTHORIZED_REQUEST = 'Merchant user is not allowed to access the resource.';

    /**
     * @uses \Spryker\Zed\ClickAndCollectExample\Business\Validator\AuthorizationValidator::ERROR_RESPONSE_CODE_MERCHANT_USER_UNAUTHORIZED_REQUEST
     *
     * @var string
     */
    protected const ERROR_RESPONSE_CODE_MERCHANT_USER_UNAUTHORIZED_REQUEST = '5700';

    /**
     * @var \SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester
     */
    protected ClickAndCollectExampleBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldSkipValidationForRequestWithoutRequestUser(): void
    {
        // Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setRequestUser(null);

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateProtectedGlueRequest(
            $glueRequestTransfer,
            $this->createResourceMock(),
        );

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testShouldSkipValidationForRequestWithoutSurrogateIdentifier(): void
    {
        // Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier(null));

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateProtectedGlueRequest(
            $glueRequestTransfer,
            $this->createResourceMock(),
        );

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessValidationForUserWithoutMerchantUserRelation(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::STATUS => static::USER_STATUS_ACTIVE]);
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier($userTransfer->getIdUserOrFail()));

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateProtectedGlueRequest(
            $glueRequestTransfer,
            $this->createResourceMock(),
        );

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testShouldReturnFailedValidationForUserWithMerchantUserRelation(): void
    {
        // Arrange
        $merchantUserTransfer = $this->createMerchantUser();
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setRequestUser((new GlueRequestUserTransfer())->setSurrogateIdentifier($merchantUserTransfer->getIdUserOrFail()));

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateProtectedGlueRequest(
            $glueRequestTransfer,
            $this->createResourceMock(),
        );

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsValid());
        $this->assertSame(
            static::ERROR_MESSAGE_MERCHANT_USER_UNAUTHORIZED_REQUEST,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getStatus(),
        );
        $this->assertSame(
            static::ERROR_RESPONSE_CODE_MERCHANT_USER_UNAUTHORIZED_REQUEST,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getCode(),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function createMerchantUser(): MerchantUserTransfer
    {
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $userTransfer = $this->tester->haveUser([UserTransfer::STATUS => static::USER_STATUS_ACTIVE]);

        return $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResourceMock(): ResourceInterface
    {
        return $this->getMockBuilder(ResourceInterface::class)->getMock();
    }
}
