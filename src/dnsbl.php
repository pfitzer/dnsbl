<?php
/**
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

/**
 * checks a list of dnsbl services
 *
 * Class Dnsbl
 * @package DnsBl
 */
class Dnsbl
{
    /**
     * @var array
     */
    private $blackLists = array(
        "all.s5h.net",
        "b.barracudacentral.org",
        "bl.emailbasura.org",
        "bl.spamcannibal.org",
        "bl.spamcop.net",
        "blackholes.five-ten-sg.com",
        "blacklist.woody.ch",
        "bogons.cymru.com",
        "cbl.abuseat.org",
        "cdl.anti-spam.org.cn",
        "combined.abuse.ch",
        "db.wpbl.info",
        "dnsbl-1.uceprotect.net",
        "dnsbl-2.uceprotect.net",
        "dnsbl-3.uceprotect.net",
        "dnsbl.anticaptcha.net",
        "dnsbl.cyberlogic.net",
        "dnsbl.dronebl.org",
        "dnsbl.inps.de",
        "dnsbl.sorbs.net",
        "drone.abuse.ch",
        "duinv.aupads.org",
        "dul.dnsbl.sorbs.net",
        "dyna.spamrats.com",
        "dynip.rothen.com",
        "exitnodes.tor.dnsbl.sectoor.de",
        "http.dnsbl.sorbs.net",
        "ips.backscatterer.org",
        "ix.dnsbl.manitu.net",
        "korea.services.net",
        "misc.dnsbl.sorbs.net",
        "noptr.spamrats.com",
        "orvedb.aupads.org",
        "pbl.spamhaus.org",
        "proxy.bl.gweep.ca",
        "psbl.surriel.com",
        "rbl.interserver.net",
        "rbl.megarbl.net",
        "relays.bl.gweep.ca",
        "relays.bl.kundenserver.de",
        "relays.nether.net",
        "sbl.spamhaus.org",
        "service.mailblacklist.com",
        "short.rbl.jp",
        "singular.ttk.pte.hu",
        "smtp.dnsbl.sorbs.net",
        "socks.dnsbl.sorbs.net",
        "spam.abuse.ch",
        "spam.dnsbl.sorbs.net",
        "spam.spamrats.com",
        "spambot.bls.digibase.ca",
        "spamrbl.imp.ch",
        "spamsources.fabel.dk",
        "ubl.lashback.com",
        "ubl.unsubscore.com",
        "virbl.bit.nl",
        "virus.rbl.jp",
        "wormrbl.imp.ch",
        "xbl.spamhaus.org",
        "zen.spamhaus.org",
        "zombie.dnsbl.sorbs.net",
        "bad.psky.me"
    );

    /**
     * if you give an array of blacklists the internal ones will be ignored
     * set $append to true to add your list to the internal
     *
     * @param array $blacklists
     * @param boolean $append
     */
    public function __construct($blacklists = null, $append = false) {
        if (is_array($blacklists)) {
            if(!$append) {
                $this->blackLists = $blacklists;
            } else {
                $this->blackLists = array_merge($this->blackLists, $blacklists);
            }
        }
    }

    /**
     * @param string $blacklist
     */
    public function addBlacklist($blacklist) {
        if (is_string($blacklist)) {
            array_push($this->blackLists, $blacklist);
        }
    }

    /**
     * @return array
     */
    public function getBlackLists() {
        return $this->blackLists;
    }

    /**
     * reverse the ip
     *
     * e.g.: 127.0.0.1 => 1.0.0.127
     *
     * @param string $lookupIp
     * @return string
     */
    public function reverseIp($lookupIp) {
        $this->validateIp($lookupIp);
        $parts = explode('.', $lookupIp);
        return implode('.', array_reverse($parts));
    }

    /**
     * check a single ip for blacklisted
     *
     * returns array with the blacklist as key and the listing as boolean value
     *
     *
     * @param string $lookupIp
     * @param string $type
     * @return array
     */
    public function lookup($lookupIp, $type='A') {
        $result = array();
        foreach ($this->blackLists as $bl) {
            $res = checkdnsrr($this->reverseIp($lookupIp). '.' . $bl, $type);
            if (is_bool($res)) {
                $result[$bl] = $res;
            }
        }

        return $result;
    }

    /**
     * @param string $lookupIp
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function validateIp($lookupIp) {
        $valid = filter_var($lookupIp, FILTER_VALIDATE_IP);
        if (!$valid) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid ip address!', $lookupIp));
        }
    }
}