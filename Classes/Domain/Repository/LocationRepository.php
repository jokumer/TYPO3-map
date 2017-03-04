<?php
namespace Jokumer\Map\Domain\Repository;

use Jokumer\Map\Domain\Repository\AbstractRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class LocationRepository
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LocationRepository extends AbstractRepository
{
    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param \array $radialSearchParameters The search parameters as array
     * @return QueryResultInterface The Locations
     * @author Stephan Kellermayr <stephan.kellermayr@gmail.com> sonority.at
     */
    public function findLocationsInRadius($radialSearchParameters)
    {
        $latitude = floatval($radialSearchParameters['latitude']);
        $longitude = floatval($radialSearchParameters['longitude']);
        $radius = intval($radialSearchParameters['radius']);
        $limit = !empty($radialSearchParameters['limit']) ? intval($radialSearchParameters['limit']) : null;
        $orderBy = !empty($radialSearchParameters['orderBy']) ? $radialSearchParameters['orderBy'] : 'distance';
        $orderBy .= ' ' . ((strtolower($this->settings['sortOrder']) === 'desc') ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING);
        $calulcateDistance = intval($radialSearchParameters['calulcateDistance']);
        $kilometer = intval($radialSearchParameters['kilometer']);
        // Categories
        $categories = GeneralUtility::intExplode(',', $radialSearchParameters['categories'], true);
        $categoryCount = count($categories);
        // StorgaePid
        $configurationManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class);
        $frameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $storagePid = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $frameworkConfiguration['persistence']['storagePid']);
        $storagePids = implode(',', $storagePid);
        $pidList = GeneralUtility::intExplode(',', $storagePids, true);
        $pidCount = count($pidList);
        if ($pidCount && $radius) {
            $query = $this->createQuery();
            $tableName = 'tx_map_location';
            $statement = 'SELECT *,
                (
                    6371 * acos(
                        cos(
                            radians(' . $latitude . ')
                        ) * cos(
                            radians(' . $tableName . '.latitude)
                        ) * cos(
                            radians(' . $tableName . '.longitude) - radians(' . $longitude . ')
                        ) + sin(
                            radians(' . $latitude . ')
                        ) * sin(
                            radians(' . $tableName . '.latitude)
                        )
                    )
                ) AS distance FROM ' . $tableName . ' ';

            #if ($categoryCount) {
            #    $statement .= 'LEFT JOIN tx_geolocations_location_category_mm AS mm ON ' . $tableName . '.uid = mm.uid_local ';
            #}

            $statement .= 'WHERE ';
            if ($pidCount > 1) {
                $statement .= '' . $tableName . '.pid IN (' . implode(',', $pidList) . ') ';
            } elseif ($pidCount === 1) {
                $statement .= '' . $tableName . '.pid=' . implode(',', $pidList) . ' ';
            }
            if ($categoryCount > 1) {
                $statement .= 'AND mm.uid_foreign IN (' . implode(',', $categories) . ') ';
            } elseif ($categoryCount === 1) {
                $statement .= 'AND mm.uid_foreign=' . implode(',', $categories) . ' ';
            }
            $statement .= $GLOBALS['TSFE']->sys_page->enableFields($tableName) . ' ';
            $statement .= 'HAVING distance < ' . $radius . ' ORDER BY distance ';
            if ($limit) {
                $statement .= 'LIMIT ' . $limit;
            }
            // Set query-statement
            $query->statement($statement);
            // Execute query
            $result = $query->execute();
            // Calulate distance to current coordinates
            if ($calulcateDistance) {
                foreach ($result as $location) {
                    $location->setDistance($latitude, $longitude, $kilometer);
                }
            }
            return $result;
        }
        return false;
    }
}
