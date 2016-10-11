<?php
/**
 * Created by PhpStorm.
 * User: micpfist
 * Date: 10.10.16
 * Time: 15:06
 */
namespace DnsBl;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit_Framework_TestCase;
use phpmock\phpunit\PHPMock;

class DnsblTest extends PHPUnit_Framework_TestCase {

    use PHPMock;

    private $defaultCount;

    function setup() {
        $dnsbl = new Dnsbl();
        $this->defaultCount = count($dnsbl->getBlackLists());
    }

    /**
     *
     */
    function testServerConst() {
        $dnsbl = new Dnsbl();
        $this->assertTrue(is_array($dnsbl->getBlackLists()));
    }

    /**
     *
     */
    function testReverseIp() {
        $dnsbl = new Dnsbl();
        $this->assertEquals('1.0.0.127', $dnsbl->reverseIp('127.0.0.1'));
    }

    /**
     *
     */
    function testAddBlacklist() {
        $dnsbl = new Dnsbl(array('foo.bar.com'));
        $this->assertEquals(1, count($dnsbl->getBlackLists()));
        $dnsbl->addBlacklist('bar.foo.com');
        $this->assertEquals(array('foo.bar.com', 'bar.foo.com'), $dnsbl->getBlackLists());
    }

    /**
     *
     */
    function testMergeBlacklist() {
        $dnsbl = new Dnsbl(array('foo.bar.com', 'bar.foo.com'), true);
        $this->assertEquals($this->defaultCount + 2, count($dnsbl->getBlackLists()));
    }

    /**
     *
     */
    function testLookup() {
        $dnsrr = $this->getFunctionMock(__NAMESPACE__, 'checkdnsrr');
        $dnsrr->expects($this->once())->willReturn(true);
        $dnsbl = new Dnsbl(array('foo.bar.com'));
        $res = $dnsbl->lookup('127.0.0.1');
        $this->assertEquals(array('foo.bar.com' => true), $res);
    }
}
