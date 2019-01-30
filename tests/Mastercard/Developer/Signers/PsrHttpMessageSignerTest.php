<?php

namespace Mastercard\Developer\Signers;

use Mastercard\Developer\OAuth\Test\TestUtils;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Request; // GuzzleHttp requests are implementing the PSR RequestInterface

class PsrHttpMessageSignerTest extends TestCase {

    public function testSign_ShouldAddOAuth1HeaderToRequest_WhenValidInputs() {

        // GIVEN
        $signingKey = TestUtils::getTestPrivateKey();
        $consumerKey = 'Some key';
        $body = '{"foo":"bår"}';
        $headers = ['Content-Type' => 'application/json'];
        $request = new Request('POST', 'https://api.mastercard.com/service', $headers, $body);

        // WHEN
        $instanceUnderTest = new PsrHttpMessageSigner($consumerKey, $signingKey);
        $instanceUnderTest->sign($request);

        // THEN
        $authorizationHeaderValue = $request->getHeader("Authorization")[0];
        $this->assertNotNull($authorizationHeaderValue);
        $this->assertEquals(0, strpos($authorizationHeaderValue, "OAuth"));
    }

    public function testSign_ShouldSupportRequestsWithoutBody() {

        // GIVEN
        $signingKey = TestUtils::getTestPrivateKey();
        $consumerKey = 'Some key';
        $request = new Request('GET', 'https://api.mastercard.com/service');

        // WHEN
        $instanceUnderTest = new PsrHttpMessageSigner($consumerKey, $signingKey);
        $instanceUnderTest->sign($request);

        // THEN
        $authorizationHeaderValue = $request->getHeader("Authorization")[0];
        $this->assertNotNull($authorizationHeaderValue);
        $this->assertEquals(0, strpos($authorizationHeaderValue, "OAuth"));
    }
}