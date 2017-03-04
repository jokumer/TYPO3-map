<?php
namespace Jokumer\Map\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * Class Category
 * 
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Category extends AbstractEntity
{

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Icon
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     * @lazy
     */
    protected $icon;

    /**
     * Parent
     *
     * @var \Jokumer\Map\Domain\Model\Category|null
     * @lazy
     */
    protected $parent = null;

    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * TitleLanguageOverlay
     *
     * @var string
     */
    protected $titleLanguageOverlay = '';

    /**
     * IsActive
     *
     * @var boolean
     */
    protected $isActive = false;

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
     * Returns the icon
     *
     * @return FileReference $icon
     */
    public function getIcon()
    {
        if (!is_object($this->icon)){
            return null;
        } elseif ($this->icon instanceof LazyLoadingProxy) {
            $this->icon->_loadRealInstance();
        }
        return $this->icon;
    }

    /**
     * Sets the icon
     *
     * @param FileReference $icon
     * @return void
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Returns the parent
     *
     * @return Category|null $parent
     */
    public function getParent()
    {
        if ($this->parent instanceof LazyLoadingProxy) {
            $this->parent->_loadRealInstance();
        }
        return $this->parent;
    }

    /**
     * Sets the parent
     *
     * @param Category $parent
     * @return void
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
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
     * Returns the titleLanguageOverlay
     *
     * @return string $titleLanguageOverlay
     */
    public function getTitleLanguageOverlay()
    {
        return $this->titleLanguageOverlay;
    }

    /**
     * Sets the titleLanguageOverlay
     *
     * @param string $titleLanguageOverlay
     * @return void
     */
    public function setTitleLanguageOverlay($titleLanguageOverlay)
    {
        $this->titleLanguageOverlay = $titleLanguageOverlay;
    }

    /**
     * Returns the isActive
     *
     * @return boolean $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Sets the isActive
     *
     * @param boolean $isActive
     * @return void
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
}
