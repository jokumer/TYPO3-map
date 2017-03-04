<?php
namespace Jokumer\Map\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FormatViewHelper
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FormatViewHelper extends AbstractViewHelper
{

    /**
     * Initialize additional arguments
     */
    public function initializeArguments() {
        $this->registerArgument('type', NULL, 'Type', FALSE, array());
    }
    
    /**
     * Render
     * Returns value formated via given method
     *
     * @param string $value
     * @param string $method
     * @return string
     */
    public function render($value, $method) {
        if (method_exists($this, $method)) {
            $value = $this->$method($value);
        }
        return $value;
    }

    /**
     * Convert string into three integers
     * Converts a integer value to 3 ciffres by pushing zeros to the first chars ('12' -> '012'),
     * as used in nautical descriptions for directions
     *
     * @param string $value to convert
     * @return string converted $value
     */
    private function convertStringIntoThreeIntegers($value)
    {
        if (!$value) {
            return '0';
        }
        return sprintf('%03d', $value);
    }

    /**
     * Convert litude decimal to nautical format
     * Converts an decimal integer value of latidude or longitude into a nautical format (58.7280555556 -> 058' 43.68")
     *
     * @param string $value to convert
     * @return string converted $value
     */
    private function convertLitudeDecimalToNauticalFormat($value)
    {
        if (!$value) {
            return 'NO DATA';
        }
        // Get hemisphere
        $hemisphere = '';
        if (isset($this->arguments['type'])) {
            switch ($this->arguments['type']) {
                case 'latitude':
                    if (strpos(trim($value), '-') === false) {
                        $hemisphere = 'N';
                    } else {
                        $hemisphere = 'S';
                    }
                    
                    break;
                case 'longitude':
                    if (strpos(trim($value), '-') === false) {
                        $hemisphere = 'E';
                    } else {
                        $hemisphere = 'W';
                    }
                    break;
            }
            if ($hemisphere) {
                // Make real integer
                $value = str_replace('-', '', $value);
                $hemisphere = ' ' . $hemisphere;
            }
        }
        // Get nautical number format
        $decimal = doubleval($value);
        $degree = floor($decimal);
        $minute = (doubleval($value) - $degree) * 60;
        $second = round((((doubleval($value) - $degree) * 60) - floor($minute)) * 100);
        return sprintf('%03d&deg; %02d.%d&quot;', $degree, $minute, $second) . $hemisphere;

    }

    /**
     * Converts decimal to sexagesimal
     *
     * @param string $value
     * @return string
     */
    private function convertDecimalToSexagesimal($value)
    {
        if (!is_numeric($value)) {
            return false;
        }
        $degree = floor($value);
        $value = ($value - $degree) * 60;
        $minute = floor($value);
        $value = ($value - $minute) * 60;
        $second = round($value);
        return sprintf('%03d&deg; %02d&quot; %02d&#39;', $degree, $minute, $second);
    }

    /**
     * Converts sexagesimal to decimal
     *
     * @param string $value
     * @return string
     */
    private function convertSexagesimalToDecimal($value)
    {
        if (!preg_match('/^(\d+)\D+(\d{1,2})\D+(\d{1,2})\D+$/', $value, $matches)) {
            return false;
        }
        return $matches[1] + ($matches[2] + $matches[3] / 60) / 60;
    }
}
