<?php
/**
 * Created by PhpStorm.
 * User: micpfist
 * Date: 10.10.16
 * Time: 15:06
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use DnsBl\Dnsbl;

class DnsblTest extends TestCase {

    function testServerConst() {
        $dnsbl = new Dnsbl();
        $this->assertTrue(is_array($dnsbl->getBlacklists()));
    }

    function testReverseIp() {
        $dnsbl = new Dnsbl();
        $this->assertEquals('1.0.0.127', $dnsbl->reverseIp('127.0.0.1'));
    }

    function testAddBlacklist() {
        $dnsbl = new Dnsbl(array('foo.bar.com'));
        $this->assertEquals(1, count($dnsbl->getBlacklists()));
        $dnsbl->addBlacklist('bar.foo.com');
        $this->assertEquals(array('foo.bar.com', 'bar.foo.com'), $dnsbl->getBlacklists());
    }
}
