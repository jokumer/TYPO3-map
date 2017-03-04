<?php
namespace Jokumer\Map\Domain\Model\Overlay;

use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * Class MapOverlay
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MapOverlay
{

    /**
     * Categories
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Markers
     *
     * @var array
     */
    protected $markers = [];

    /**
     * Returns the categories
     *
     * @return array $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param QueryResult|array $categories
     * @return void
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Add categories
     *
     * @param QueryResult|array $categories
     * @return void
     */
    public function addCategories($categories)
    {
        if ($categories instanceof QueryResult) {
            $categories = $categories->toArray();
        }
        if (is_array($categories) && !empty($categories)) {
            $this->categories = array_merge($this->categories, $categories);
        }
    }

    /**
     * Returns the markers
     *
     * @return array $markers
     */
    public function getMarkers()
    {
        return $this->markers;
    }
    
    /**
     * Sets the markers
     *
     * @param QueryResult|array $markers
     * @return void
     */
    public function setMarkers($markers)
    {
        $this->markers = $markers;
    }

    /**
     * Add markers
     *
     * @param QueryResult|array $markers
     * @return void
     */
    public function addMarkers($markers)
    {
        if ($markers instanceof QueryResult) {
            $markers = $markers->toArray();
        }
        if (is_array($markers) && !empty($markers)) {
            $this->markers = array_merge($this->markers, $markers);
        }
    }

}