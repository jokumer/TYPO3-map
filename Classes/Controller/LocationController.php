<?php
namespace Jokumer\Map\Controller;

use Jokumer\Map\Controller\AbstractController;
use Jokumer\Map\Domain\Model\Location;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;

/**
 * Class LocationController
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LocationController extends AbstractController
{
    /**
     * Action list
     *
     * @return void
     */
    public function listAction()
    {
        $filter = null;
        $locations = $this->getLocations($filter);
        $this->view->assign('locations', $locations);
    }

    /**
     * Action list geolocate
     *
     * @return void
     */
    public function listGeolocateAction()
    {
        $filter = null;
        if ($this->settings['location']['options']['geolocate'] && $this->request->hasArgument('geolocate')) {
            $geolocateRequest = $this->request->getArgument('geolocate');
            if (!empty($geolocateRequest)) {
                $filter = 'radial';
                if ($geolocateRequest['latitude'] !== 0) {
                    $this->geolocationLatitude = $geolocateRequest['latitude'];
                }
                if ($geolocateRequest['longitude'] !== 0) {
                    $this->geolocationLongitude = $geolocateRequest['longitude'];
                }
            }
        }
        $locations = $this->getLocations($filter);
        $this->view->assign('locations', $locations);
    }

    /**
     * Action show
     *
     * @param Location $location
     * @return void
     */
    public function showAction(Location $location)
    {
        $this->view->assign('location', $location);
        if ($this->view instanceof JsonView) {
            $this->view->setVariablesToRender(array('location'));
        }
    }
}
