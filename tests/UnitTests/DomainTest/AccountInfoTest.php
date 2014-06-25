<?php
use YandexMoney\Domain\AccountInfo;
use YandexMoney\Domain\Avatar;
use YandexMoney\Domain\BalanceDetails;
use YandexMoney\Domain\LinkedCard;

/**
 * Created by PhpStorm.
 * Authors: Eugene Avrukevich <eugene.avrukevich@gmail.com>
 * Date: 5/31/14
 * Time: 3:06 PM
 */
class AccountInfoTest extends PHPUnit_Framework_TestCase
{

    protected $accountInfo;

    public function setUp()
    {
        $this->accountInfo = new AccountInfo(array(
            AccountInfo::ACCOUNT => "1234qwerty",
            AccountInfo::BALANCE => 130.50,
            AccountInfo::CURRENCY => 643,
            AccountInfo::ACCOUNT_STATUS => "anonymous",
            AccountInfo::ACCOUNT_TYPE => "personal",
            AccountInfo::AVATAR => array(
                Avatar::URL => "http://www.example.com/avatar.jpg",
                Avatar::TIMESTAMP => "2014-06-01 06:35:45"
            ),
            AccountInfo::BALANCE_DETAILS => array(
                BalanceDetails::TOTAL => 200.50,
                BalanceDetails::AVAILABLE => 120.40,
                BalanceDetails::DEPOSITION_PENDING => 140.40,
                BalanceDetails::BLOCKED => 50.55,
                BalanceDetails::DEBT => 15.15
            ),
            AccountInfo::CARDS_LINKED => array(
                array(
                    LinkedCard::PAN_FRAGMENT => "510000******9999",
                    LinkedCard::TYPE => "MasterCard"
                ),

            ),
            AccountInfo::SERVICES_ADDITIONAL => array(
                array(
                    "link_alfabank",
                )
            )
        ));
    }

    public function testGetAccount()
    {
        $this->assertEquals($this->accountInfo->getAccount(), "1234qwerty");
    }

    public function testGetBalance()
    {
        $this->assertEquals($this->accountInfo->getBalance(), 130.50);
    }

    public function testGetCurrency()
    {
        $this->assertEquals($this->accountInfo->getCurrency(), 643);
    }

    public function testAccountStatus()
    {
        $this->assertEquals($this->accountInfo->getAccountStatus(), 'anonymous');
    }

    public function testAccountType()
    {
        $this->assertEquals($this->accountInfo->getAccountType(), 'personal');
    }

    public function testGetAvatar()
    {
        $this->assertNotNull($this->accountInfo->getAvatar());
        $this->assertInstanceOf('YandexMoney\Domain\Avatar', $this->accountInfo->getAvatar());
    }

    public function testGetBalanceDetails()
    {
        $this->assertNotNull($this->accountInfo->getBalanceDetails());
        $this->assertInstanceOf('YandexMoney\Domain\BalanceDetails', $this->accountInfo->getBalanceDetails());
    }

    public function testGetCardsLinked()
    {
        $this->assertTrue(is_array($this->accountInfo->getCardsLinked()));
        $this->assertCount(1, $this->accountInfo->getCardsLinked());

        $linkedCards = $this->accountInfo->getCardsLinked();
        $this->assertInstanceOf('YandexMoney\Domain\LinkedCard', $linkedCards[0]);
    }

    public function testGetServicesAdditional()
    {
        $this->assertTrue(is_array($this->accountInfo->getServicesAdditional()));
        $this->assertCount(1, $this->accountInfo->getServicesAdditional());
    }

    public function tearDown()
    {
        $this->accountInfo = null;
    }

} 