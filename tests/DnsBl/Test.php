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

require_once __DIR__ . '/../../vendor/autoload.php';

use InvalidArgumentException;
use phpmock\mockery\PHPMockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use phpmock\phpunit\PHPMock;

#[CoversClass(Dnsbl::class)]
class DnsblTest extends TestCase {

    use PHPMock;

    private $defaultCount;

    function setup(): void
    {
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
     * @return void
     */
    function testAddBlacklistWrongFormatException() {
        $this->expectException(InvalidArgumentException::class);
        $dnsbl = new Dnsbl();
        $dnsbl->addBlacklist('http://test.com');
    }

    /**
     *
     */
    function testMergeBlacklist() {
        $dnsbl = new Dnsbl(array('foo.bar.com', 'bar.foo.com'), true);
        $this->assertEquals($this->defaultCount + 2, count($dnsbl->getBlackLists()));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testLookup() {
        $dnsbl = new Dnsbl();
        $expectedResult = [
            'all.s5h.net' => false,
            'b.barracudacentral.org' => false,
            //Other blacklists go here
        ];

        $lookupMethodResult = $dnsbl->lookup('8.8.8.8', 'A');

        //Assert each provider returned a boolean
        foreach($lookupMethodResult as $provider => $isListed) {
            $this->assertIsBool($isListed);
        }

        //Assert the expected providers are being checked
        foreach($expectedResult as $provider => $expectedListed) {
            $this->assertArrayHasKey($provider, $lookupMethodResult, "The provider $provider was not included in the lookup method result.");
        }
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testLookupReturnNotBoolean() {
        $dnsrr = PHPMockery::mock(__NAMESPACE__, 'checkdnsrr')->andReturn('foo');
        $dnsbl = new Dnsbl(array('foo.bar.com', 'http://foo.bar.com'));
        $res = $dnsbl->lookup('127.0.0.1');
        $this->assertEquals(array(), $res);
    }

    /**
     * @return void
     */
    function testValideIp() {
        $this->expectException(InvalidArgumentException::class);
        $dnsbl = new Dnsbl();
        $dnsbl->reverseIp('aa');
    }
}
