<?php
/**
 * Created by PhpStorm.
 * User: micpfist
 * Date: 10.10.16
 * Time: 14:59
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
    private $_blacklists = array(
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
                $this->_blacklists = $blacklists;
            } else {
                $this->_blacklists = array_merge($this->_blacklists, $blacklists);
            }
        }
    }

    /**
     * @param string $blacklist
     */
    public function addBlacklist($blacklist) {
        array_push($this->_blacklists, $blacklist);
    }

    /**
     * @return array
     */
    public function getBlacklists() {
        return $this->_blacklists;
    }

    /**
     * reverse the ip
     *
     * e.g.: 127.0.0.1 => 1.0.0.127
     *
     * @param string $ip
     * @return string
     */
    public function reverseIp($ip) {
        $parts = explode('.', $ip);
        return implode('.', array_reverse($parts));
    }

    /**
     * check a single ip for blacklisted
     *
     * returns array with the blacklist as key and the listing as boolean value
     *
     *
     * @param string $ip
     * @param string $type
     * @return array
     */
    public function lookup($ip, $type='A') {
        $result = array();
        foreach ($this->_blacklists as $bl) {
            $res = checkdnsrr($this->reverseIp($ip). '.' . $bl, $type);
            $result[$bl] = $res;
        }

        return $result;
    }
}