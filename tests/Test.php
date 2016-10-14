<?php
/**
 * @author Michael Pfister <michael@mp-development.de>
 * @copyright (c) 2016, Michael Pfister
 * @license MIT
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF
 * ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * https://opensource.org/licenses/MIT
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
        $dnsbl->addBlacklist(true);
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

    function testLookupReturnNotBoolean() {
        $dnsrr = $this->getFunctionMock(__NAMESPACE__, 'checkdnsrr');
        $dnsrr->expects($this->once())->willReturn('foo');
        $dnsbl = new Dnsbl(array('foo.bar.com'));
        $res = $dnsbl->lookup('127.0.0.1');
        $this->assertEquals(array(), $res);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "aa" is not a valid ip address!
     */
    function testValideIp() {
        $dnsbl = new Dnsbl();
        $dnsbl->reverseIp('aa');
    }
}
