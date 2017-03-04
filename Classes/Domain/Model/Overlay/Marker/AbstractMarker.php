<?php
namespace Jokumer\Map\Domain\Model\Overlay\Marker;

use Jokumer\Map\Domain\Model\Category;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * Class AbstractMarker
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class AbstractMarker extends AbstractEntity
{

    /**
     * Category
     *
     * @var \Jokumer\Map\Domain\Model\Category|null
     * @lazy
     */
    protected $category = null;

    /**
     * DateTime
     *
     * @var \DateTime
     */
    protected $dateTime = null;

    /**
     * Icon
     * Public url of FAL resource
     *
     * @var string
     */
    protected $icon = '';

    /**
     * Identifier
     * Short class name and the uid of the object as string
     *
     * @var string
     */
    protected $identifier = '';

    /**
     * Latitude
     *
     * @var float
     */
    protected $latitude = 0.0;

    /**
     * Longitude
     *
     * @var float
     */
    protected $longitude = 0.0;
    
    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Returns the category
     *
     * @return Category $category
     */
    public function getCategory()
    {
        if ($this->category instanceof LazyLoadingProxy) {
            $this->category->_loadRealInstance();
        }
        return $this->category;
    }

    /**
     * Returns the dateTime
     *
     * @return \Date $dateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Returns the icon
     *
     * @return string $icon
     */
    public function getIcon()
    {
        if ($this->category instanceof LazyLoadingProxy) {
            $this->category->_loadRealInstance();
        }
        if ($this->category) {
            return $this->category->getIcon()->getOriginalResource()->getOriginalFile()->getPublicUrl();
        } else {
            return null;
        }
    }

    /**
     * Returns the short class name and the uid of the object as string
     * For full class name use AbstractEntity::__toString();
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        $reflectionClass = new \ReflectionClass($this);
        return $reflectionClass->getShortName() . '|' . (string)$this->uid;
    }

    /**
     * Returns the latitude
     *
     * @return float $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns the longitude
     *
     * @return float $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        if ($this->title) {
            return $this->title;
        } else {
            if ($this->getDateTime()) {
                return $this->getDateTime()->format('d/m/Y H:i');
            }
        }
    }
}