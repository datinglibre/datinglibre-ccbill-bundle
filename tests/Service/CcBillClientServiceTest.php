<?php

declare(strict_types=1);

namespace DatingLibre\CcBillBundle\Tests\Service;

use DatingLibre\CcBillBundle\Service\CcBillClientService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CcBillClientServiceTest extends TestCase
{
    private const VIEW_SUBSCRIPTION_STATUS_SUCCESS_PAYLOAD =<<<EOD
<?xml version='1.0' standalone='yes'?>
<results>
  <chargebacksIssued>0</chargebacksIssued>
  <nextBillingDate>20210704</nextBillingDate>
  <recurringSubscription>1</recurringSubscription>
  <refundsIssued>0</refundsIssued>
  <signupDate>20210604114528</signupDate>
  <subscriptionStatus>2</subscriptionStatus>
  <timesRebilled>0</timesRebilled>
  <voidsIssued>0</voidsIssued>
</results>
EOD;
    private const VIEW_SUBSCRIPTION_STATUS_FAILURE_PAYLOAD =<<<EOD
<?xml version='1.0' standalone='yes'?>
<results>-3</results>
EOD;

    private const TEST_USERNAME = 'dl';
    private const TEST_PASSWORD = '123abc';
    private const TEST_CLIENT_ACCOUNT = '123';
    private const TEST_CLIENT_SUB_ACCOUNT = '456';
    private const TEST_SUBSCRIPTION_ID = '5432';

    private CcBillClientService $ccBillClientService;
    private HttpClientInterface $mockHttpClient;

    public function setUp(): void
    {
        $this->mockHttpClient = $this->getMockBuilder(HttpClientInterface::class)
            ->getMock();

        $this->ccBillClientService = new CcBillClientService(
            self::TEST_USERNAME,
            self::TEST_PASSWORD,
            self::TEST_CLIENT_ACCOUNT,
            self::TEST_CLIENT_SUB_ACCOUNT,
            $this->mockHttpClient
        );
    }

    public function testViewSubscriptionStatusSuccess()
    {
        $this->mockHttpClient->method('request')
            ->willReturn($this->getMockResponse(self::VIEW_SUBSCRIPTION_STATUS_SUCCESS_PAYLOAD));

        $content = $this->ccBillClientService->viewSubscriptionStatus(self::TEST_SUBSCRIPTION_ID);

        $this->assertStringContainsString('chargebacksIssued', $content);
    }

    public function testViewSubscriptionStatusFailure()
    {
        $this->mockHttpClient->method('request')
            ->willReturn($this->getMockResponse(self::VIEW_SUBSCRIPTION_STATUS_FAILURE_PAYLOAD));

        $content = $this->ccBillClientService->viewSubscriptionStatus(self::TEST_SUBSCRIPTION_ID);

        $this->assertStringContainsString('No record was found for the given subscription.', $content);
    }

    private function getMockResponse(string $content): ResponseInterface
    {
        $mockResponse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();

        $mockResponse->method('getContent')->willReturn($content);

        return $mockResponse;
    }
}
