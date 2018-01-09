<?php
namespace Jokumer\Map\Configuration;

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class ExtConf
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author (c) 2013-2016 Stefan Froemken <sfroemken@jweiland.net>, jweiland.net
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ExtConf implements SingletonInterface
{

    /**
     * Google Maps JavaScript ApiKey
     *
     * @var string
     */
    protected $googleMapsApiKey = '';

    /**
     * Constructor of this class
     * This method reads the global configuration and calls the setter methods
     */
    public function __construct()
    {
        // get global configuration
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['map']);
        if (is_array($extConf) && count($extConf)) {
            // call setter method foreach configuration entry
            foreach ($extConf as $key => $value) {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

    /**
     * Returns the googleMapsApiKey
     *
     * @return string $googleMapsApiKey
     */
    public function getGoogleMapsApiKey()
    {
        return $this->googleMapsApiKey;
    }

    /**
     * Sets the googleMapsApiKey
     *
     * @param string $googleMapsApiKey
     * @return void
     */
    public function setGoogleMapsApiKey($googleMapsApiKey)
    {
        $this->googleMapsApiKey = trim((string)$googleMapsApiKey);
    }
}
