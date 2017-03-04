<?php
namespace Jokumer\Map\Domain\Model;

use Jokumer\Map\Domain\Model\ContentReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Location
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Location extends AbstractEntity
{

    /**
     * Address
     *
     * @var string
     */
    protected $address = '';

    /**
     * Category
     *
     * @var \Jokumer\Map\Domain\Model\Category|null
     * @lazy
     */
    protected $category = null;

    /**
     * City
     *
     * @var string
     */
    protected $city = '';

    /**
     * Country
     *
     * @var string
     */
    protected $country = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

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
     * Related content
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Jokumer\Map\Domain\Model\ContentReference>
     * @lazy
     */
    protected $relatedContent;

    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Zip
     *
     * @var string
     */
    protected $zip = '';

    /**
     * Distance
     *
     * @var float
     */
    protected $distance = null;

    /**
     * Initialize categories and media relation
     *
     * @return ContentReference
     */
    public function __construct()
    {
        $this->relatedContent = new ObjectStorage();
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

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
     * Sets the category
     *
     * @param Category $category
     * @return void
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string $country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * Sets the latitude
     *
     * @param float $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
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
     * Sets the longitude
     *
     * @param float $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get related content
     *
     * @return ObjectStorage
     */
    public function getRelatedContent()
    {
        return $this->relatedContent;
    }

    /**
     * Set related content
     *
     * @param ObjectStorage $relatedContent related content
     * @return void
     */
    public function setRelatedContent($relatedContent)
    {
        $this->relatedContent = $relatedContent;
    }

    /**
     * Adds a related content to the record
     *
     * @param ContentReference $relatedContent
     * @return void
     */
    public function addRelatedContent(ContentReference $relatedContent)
    {
        if ($this->getRelatedContent() === null) {
            $this->relatedContent = new ObjectStorage();
        }
        $this->relatedContent->attach($relatedContent);
    }

    /**
     * Get id list of related content
     *
     * @return string
     */
    public function getRelatedContentIdList()
    {
        $idList = [];
        $relatedContent = $this->getRelatedContent();
        if ($relatedContent) {
            foreach ($this->getRelatedContent() as $relatedContent) {
                $idList[] = $relatedContent->getUidLocal();
            }
        }
        return implode(',', $idList);
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the distance
     *
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Sets the distance
     *
     * @param float $lat1
     * @param float $lng1
     * @param bool $kilometer
     * @return void
     */
    public function setDistance($lat1, $lng1, $kilometer = true)
    {
        $lat2 = $this->getLatitude();
        $lng2 = $this->getLongitude();
        if ($kilometer) {
            $earthRadius = 6371;
        } else {
            $earthRadius = 3958.75;
        }
        $rad = M_PI / 180;
        $this->distance = acos(sin($lat2 * $rad) * sin($lat1 * $rad) + cos($lat2 * $rad) * cos($lat1 * $rad) * cos($lng2 * $rad - $lng1 * $rad)) * $earthRadius;
    }
}