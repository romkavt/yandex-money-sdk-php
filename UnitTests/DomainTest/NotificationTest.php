<?php
use YandexMoney\Domain\Notification;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 6/1/14
 * Time: 11:23 AM
 */
class NotificationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Notification
     */
    protected $notification;

    public function setUp()
    {
        $this->notification = new Notification(
            array(
                "operation_id" => "904035776918098009",
                "notification_type" => "p2p-incoming",
                "datetime" => "2014-04-28T16:31:28Z",
                "sha1_hash" => "8693ddf402fe5dcc4c4744d466cabada2628148c",
                "sender" => "41003188981230",
                "codepro" => false,
                "currency" => 643,
                "amount" => 0.99,
                "withdraw_amount" => 1.00,
                "label" => "",
                "lastname" => "Иванов",
                "firstname" => "Иван",
                "fathersname" => "Иванович",
                "zip" => "",
                "city" => "",
                "street" => "",
                "building" => "",
                "suite" => "",
                "flat" => "",
                "phone" => "+79253332211",
                "email" => "adress@yandex.ru"
            )
        );
    }

    public function testNotification()
    {
        $this->assertNotNull($this->notification);
    }

    public function testGetOperationId()
    {
        $this->assertNotNull($this->notification->getOperationId());
        $this->assertEquals("904035776918098009", $this->notification->getOperationId());
    }

    public function testGetNotificationType()
    {
        $this->assertNotNull($this->notification->getNotificationType());
        $this->assertEquals("p2p-incoming", $this->notification->getNotificationType());
    }

    public function testGetSha1Hash()
    {
        $this->assertNotNull($this->notification->getSha1Hash());
        $this->assertEquals("8693ddf402fe5dcc4c4744d466cabada2628148c", $this->notification->getSha1Hash());
    }

    public function testGetSender()
    {
        $this->assertNotNull($this->notification->getSender());
        $this->assertEquals("41003188981230", $this->notification->getSender());
    }

    public function testGetCodepro()
    {
        $this->assertNotNull($this->notification->getCodepro());
        $this->assertFalse($this->notification->getCodepro());
    }

    public function testGetCurrency()
    {
        $this->assertNotNull($this->notification->getCurrency());
        $this->assertEquals(643, $this->notification->getCurrency());
    }

    public function testGetAmount()
    {
        $this->assertNotNull($this->notification->getAmount());
        $this->assertEquals(0.99, $this->notification->getAmount());
    }

    public function testGetWithdrawAmount()
    {
        $this->assertNotNull($this->notification->getWithdrawAmount());
        $this->assertEquals(1.00, $this->notification->getWithdrawAmount());
    }

    public function testGetLabel()
    {
        $this->assertNotNull($this->notification->getLabel());
        $this->assertEmpty($this->notification->getLabel());
    }

    public function testGetLastName()
    {
        $this->assertNotNull($this->notification->getLastName());
        $this->assertEquals("Иванов", $this->notification->getLastName());
    }

    public function testGetFirstName()
    {
        $this->assertNotNull($this->notification->getFirstName());
        $this->assertEquals("Иван", $this->notification->getFirstName());
    }

    public function getFathersName()
    {
        $this->assertNotNull($this->notification->getFathersName());
        $this->assertEquals("Иванович", $this->notification->getFathersName());
    }

    public function testGetZip()
    {
        $this->assertNotNull($this->notification->getZip());
        $this->assertEmpty($this->notification->getZip());
    }

    public function testGetCity()
    {
        $this->assertNotNull($this->notification->getCity());
        $this->assertEmpty($this->notification->getCity());
    }

    public function testGetStreet()
    {
        $this->assertNotNull($this->notification->getStreet());
        $this->assertEmpty($this->notification->getStreet());
    }

    public function testGetBuilding()
    {
        $this->assertNotNull($this->notification->getBuilding());
        $this->assertEmpty($this->notification->getBuilding());
    }

    public function testGetSuite()
    {
        $this->assertNotNull($this->notification->getSuite());
        $this->assertEmpty($this->notification->getSuite());
    }

    public function testGetFlat()
    {
        $this->assertNotNull($this->notification->getFlat());
        $this->assertEmpty($this->notification->getFlat());
    }

    public function testGetPhone()
    {
        $this->assertNotNull($this->notification->getPhone());
        $this->assertEquals("+79253332211", $this->notification->getPhone());
    }

    public function testGetEmail()
    {
        $this->assertNotNull($this->notification->getEmail());
        $this->assertEquals("adress@yandex.ru", $this->notification->getEmail());
    }

    public function tearDown()
    {
        $this->notification = null;
    }
} 