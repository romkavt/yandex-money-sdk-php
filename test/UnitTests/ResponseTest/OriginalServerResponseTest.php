<?php
use YandexMoney\Response\OriginalServerResponse;

class OriginalServerResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var OriginalServerResponse
     */
    private $originalServerResponse;

    public function setUp()
    {
        $this->originalServerResponse = new OriginalServerResponse();
        $this->originalServerResponse->setCode(302);
        $this->originalServerResponse->setHeadersRaw(
            "HTTP/1.1 302 Found
            Server: nginx
            Date: Fri, 06 Jun 2014 18:18:27 GMT
            Content-Type: text/html
            Transfer-Encoding: chunked
            Connection: keep-alive
            Keep-Alive: timeout=120
            Cache-Control: max-age=0, proxy-revalidate
            Expires: Fri, 06 Jun 2014 18:23:27 GMT
            Location: https://money.yandex.ru/select-wallet.xml?requestid=313038323634363833375f39393063623232643562343937326333663832666537333037363935336138643961396235333439"
        );
        $this->originalServerResponse->setBodyRaw(
            '{"response":{"code":115,"status":"ok","message":"Categories ready","categories":[{"_id":1,"category":"Расходники"},{"_id":2,"category":"Детали подвески"},{"_id":3,"category":"Тормозные системы"}]}}'
        );
        $this->originalServerResponse->setErrorCode(1);
        $this->originalServerResponse->setErrorMessage("test_error");
    }

    public function testOriginalServerResponse()
    {
        $this->assertNotNull($this->originalServerResponse);
    }

    public function testGetCode()
    {
        $this->assertEquals(302, $this->originalServerResponse->getCode());
    }

    public function testGetHeadersRaw()
    {
        $this->assertEquals(
            "HTTP/1.1 302 Found
            Server: nginx
            Date: Fri, 06 Jun 2014 18:18:27 GMT
            Content-Type: text/html
            Transfer-Encoding: chunked
            Connection: keep-alive
            Keep-Alive: timeout=120
            Cache-Control: max-age=0, proxy-revalidate
            Expires: Fri, 06 Jun 2014 18:23:27 GMT
            Location: https://money.yandex.ru/select-wallet.xml?requestid=313038323634363833375f39393063623232643562343937326333663832666537333037363935336138643961396235333439",
            $this->originalServerResponse->getHeadersRaw()
        );
    }

    public function testGetHeadersArray()
    {
        $headersArray = $this->originalServerResponse->getHeadersArray();

        $this->assertTrue(is_array($headersArray));

        $this->assertArrayHasKey('Server', $headersArray);
        $this->assertEquals('nginx', $headersArray['Server']);

        $this->assertArrayHasKey('Date', $headersArray);
        $this->assertEquals('Fri, 06 Jun 2014 18:18:27 GMT', $headersArray['Date']);

        $this->assertArrayHasKey('Content-Type', $headersArray);
        $this->assertEquals('text/html', $headersArray['Content-Type']);

        $this->assertArrayHasKey('Transfer-Encoding', $headersArray);
        $this->assertEquals('chunked', $headersArray['Transfer-Encoding']);

        $this->assertArrayHasKey('Connection', $headersArray);
        $this->assertEquals('keep-alive', $headersArray['Connection']);

        $this->assertArrayHasKey('Keep-Alive', $headersArray);
        $this->assertEquals('timeout=120', $headersArray['Keep-Alive']);

        $this->assertArrayHasKey('Cache-Control', $headersArray);
        $this->assertEquals('max-age=0, proxy-revalidate', $headersArray['Cache-Control']);

        $this->assertArrayHasKey('Expires', $headersArray);
        $this->assertEquals('Fri, 06 Jun 2014 18:23:27 GMT', $headersArray['Expires']);

        $this->assertArrayHasKey('Location', $headersArray);
        $this->assertEquals(
            'https://money.yandex.ru/select-wallet.xml?requestid=313038323634363833375f39393063623232643562343937326333663832666537333037363935336138643961396235333439',
            $headersArray['Location']
        );

    }

    public function testGetBodyRaw()
    {
        $this->assertEquals(
            '{"response":{"code":115,"status":"ok","message":"Categories ready","categories":[{"_id":1,"category":"Расходники"},{"_id":2,"category":"Детали подвески"},{"_id":3,"category":"Тормозные системы"}]}}',
            $this->originalServerResponse->getBodyRaw()
        );
    }

    public function testGetBodyJsonDecoded()
    {
        $bodyJsonDecoded = $this->originalServerResponse->getBodyJsonDecoded();

        $this->assertTrue(is_array($bodyJsonDecoded));
        $this->assertArrayHasKey('response', $bodyJsonDecoded);

        $this->assertTrue(is_array($bodyJsonDecoded['response']));
        $this->assertArrayHasKey('code', $bodyJsonDecoded['response']);

        $this->assertEquals(115, $bodyJsonDecoded['response']['code']);
    }

    public function testGetErrorCode()
    {
        $this->assertEquals(1, $this->originalServerResponse->getErrorCode());
    }

    public function testGetErrorMessage()
    {
        $this->assertEquals("test_error", $this->originalServerResponse->getErrorMessage());
    }

    public function tearDown()
    {
        $this->originalServerResponse = null;
    }


} 