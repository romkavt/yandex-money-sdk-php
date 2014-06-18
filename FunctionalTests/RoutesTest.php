<?php

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 3:19 PM
 */

require_once __DIR__ . '/../sample/consts.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

define('TOKEN', "410011124546815.8577FF01B003E385496481DFC399BFA9C8AF0A9F68C505FF5622175DCD3827C36EC0BFE160D80E56ECB3262487E29F629A0D541F1C7953943A38661067AE46314CA3BB75C010B1AC207685398BD78A143F64A9AAB6F0D1700CCCA9552A8911DF8EAD546C55F92596572211EAE1A8962D6EC3776E00C1353D68AD42BA855FC62C");

/**
 * Class RoutesTest
 */
class RoutesTest extends WebTestCase
{

    public function createApplication()
    {
        return require __DIR__ . '/../sample/index.php';
    }

    public function _testAuthorizeApplicationRedirect()
    {

        $client = $this->createClient();
        $client->request('GET', '/authorize-application');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->has('Location'));
        $this->assertContains(
            'https://money.yandex.ru/',
            $client->getResponse()->headers->get('Location')
        );

    }

    public function testGetInstanceId()
    {

        $client = $this->createClient();

        $client->request('GET', '/instance-id');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertTrue(is_string($client->getResponse()->getContent()));
        $this->assertEquals(64, strlen($client->getResponse()->getContent()));

    }

    public function testOperationsHistory()
    {

        $client = $this->createClient();
        $client->request('GET', '/operation-history', array('token' => TOKEN));

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $responseArray = $this->paramsToArray($client->getResponse()->getContent());


        $this->assertArrayHasKey('operations_amount', $responseArray);
        $this->assertEquals('0', $responseArray['operations_amount']);
    }

    public function testOperationDetails()
    {
        $client = $this->createClient();
        $client->request('GET', '/operation-details', array('token' => TOKEN));

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $this->assertNotContains('status', $client->getResponse()->getContent());
        $this->assertContains('error', $client->getResponse()->getContent());
    }

    public function testAccountInfo()
    {
        $client = $this->createClient();
        $client->request('GET', '/account-info', array('token' => TOKEN));

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $responseArray = $this->paramsToArray($client->getResponse()->getContent());

        $this->assertArrayHasKey('account', $responseArray);
        $this->assertArrayHasKey('balance', $responseArray);
        $this->assertArrayHasKey('account_type', $responseArray);
        $this->assertArrayHasKey('identified', $responseArray);
        $this->assertArrayHasKey('account_status', $responseArray);
    }

    public function testRequestPayment()
    {
        $client = $this->createClient();
        $client->request('GET', '/request-payment', array('token' => TOKEN));


        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );


        $responseArray = $this->paramsToArray($client->getResponse()->getContent());

        $this->assertArrayHasKey('status', $responseArray);
        $this->assertEquals('success', $responseArray['status']);
        $this->assertArrayHasKey('balance', $responseArray);
        $this->assertArrayHasKey('contract', $responseArray);
        $this->assertEquals("The+generated+test+payment+to+pattern+337", $responseArray['contract']);
        $this->assertArrayHasKey('request_id', $responseArray);
        $this->assertEquals('test-shop', $responseArray['request_id']);
        $this->assertArrayHasKey('test_payment', $responseArray);
        $this->assertEquals('true', $responseArray['test_payment']);
        $this->assertArrayHasKey('contract_amount', $responseArray);
        $this->assertEquals(2, $responseArray['contract_amount']);
    }

    public function testProcessPayment()
    {
        $client = $this->createClient();
        $client->request('GET', '/process-payment', array('token' => TOKEN));

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $responseArray = $this->paramsToArray($client->getResponse()->getContent());

        $this->assertArrayHasKey('status', $responseArray);
        $this->assertEquals('success', $responseArray['status']);
        $this->assertArrayHasKey('balance', $responseArray);
        $this->assertArrayHasKey('invoice_id', $responseArray);
        $this->assertEquals(0, $responseArray['invoice_id']);
        $this->assertArrayHasKey('test_payment', $responseArray);
        $this->assertEquals('true', $responseArray['test_payment']);
        $this->assertArrayHasKey('payment_id', $responseArray);
        $this->assertEquals('test', $responseArray['payment_id']);

    }

    public function testRequestPaymentError()
    {
        $client = $this->createClient();
        $client->request('GET', '/request-payment-error', array('token' => TOKEN));


        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $responseArray = $this->paramsToArray($client->getResponse()->getContent());

        $this->assertArrayHasKey('status', $responseArray);
        $this->assertEquals('refused', $responseArray['status']);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertEquals("illegal_param_amount_due", $responseArray['error']);
        $this->assertArrayHasKey('test_payment', $responseArray);
        $this->assertEquals('true', $responseArray['test_payment']);
        $this->assertArrayHasKey('error_description', $responseArray);
        $this->assertEquals('requested+error', $responseArray['error_description']);
    }

    public function testProcessPaymentError()
    {
        $client = $this->createClient();
        $client->request('GET', '/process-payment-error', array('token' => TOKEN));

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $responseArray = $this->paramsToArray($client->getResponse()->getContent());

        $this->assertArrayHasKey('status', $responseArray);
        $this->assertEquals('refused', $responseArray['status']);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertEquals("authorization_reject", $responseArray['error']);
        $this->assertArrayHasKey('test_payment', $responseArray);
        $this->assertEquals('true', $responseArray['test_payment']);
        $this->assertArrayHasKey('error_description', $responseArray);
        $this->assertEquals(
            'illegal+parameter+test_result%3D%27illegal_param_amount_due%27',
            $responseArray['error_description']
        );
    }

    protected function paramsToArray($paramsString)
    {
        $outputArray = array();
        $keyValuePairArray = explode("&", $paramsString);
        foreach ($keyValuePairArray as $pair) {
            $keyValueArray = explode("=", $pair);
            $outputArray[$keyValueArray[0]] = $keyValueArray[1];
        }
        return $outputArray;
    }
}