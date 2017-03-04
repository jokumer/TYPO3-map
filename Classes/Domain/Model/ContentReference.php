<?php
namespace Jokumer\Map\Domain\Model;

use Jokumer\Map\Domain\Model\ContentReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Location
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ContentReference extends AbstractEntity
{
    /**
     * Fieldname
     *
     * @var string
     */
    protected $fieldname;

    /**
     * Sorting foreign
     *
     * @var integer
     */
    protected $sortingForeign;

    /**
     * TableLocal
     *
     * @var string
     */
    protected $tableLocal;

    /**
     * Tablenames
     *
     * @var string
     */
    protected $tablenames;

    /**
     * Uid foreign
     *
     * @var integer
     */
    protected $uidForeign;

    /**
     * Uid local
     *
     * @var integer
     */
    protected $uidLocal;

    /**
     * Get fieldname
     *
     * @return string
     */
    public function getFieldname()
    {
        return $this->fieldname;
    }

    /**
     * Set fieldname
     *
     * @param string $fieldname
     * @return void
     */
    public function setFieldname($fieldname)
    {
        $this->fieldname = $fieldname;
    }

    /**
     * Get sorting foreign
     *
     * @return integer
     */
    public function getSortingForeign()
    {
        return $this->sortingForeign;
    }

    /**
     * Set sorting foreign
     *
     * @param integer $sortingForeign
     * @return void
     */
    public function setSortingForeign($sortingForeign)
    {
        $this->sortingForeign = $sortingForeign;
    }

    /**
     * Get table local
     *
     * @return string
     */
    public function getTableLocal()
    {
        return $this->tableLocal;
    }

    /**
     * Set table local
     *
     * @param string $tableLocal
     * @return void
     */
    public function setTableLocal($tableLocal)
    {
        $this->tableLocal = $tableLocal;
    }

    /**
     * Get tablenames
     *
     * @return string
     */
    public function getTablenames()
    {
        return $this->tablenames;
    }

    /**
     * Set tablenames
     *
     * @param string $tablenames
     * @return void
     */
    public function setTablenames($tablenames)
    {
        $this->tablenames = $tablenames;
    }

    /**
     * Get uid foreign
     *
     * @return integer
     */
    public function getUidForeign()
    {
        return $this->uidForeign;
    }

    /**
     * Set uid foreign
     *
     * @param integer $uidForeign
     * @return void
     */
    public function setUidForeign($uidForeign)
    {
        $this->uidForeign = $uidForeign;
    }

    /**
     * Get uid local
     *
     * @return integer
     */
    public function getUidLocal()
    {
        return $this->uidLocal;
    }

    /**
     * Set uid local
     *
     * @param integer $uidLocal
     * @return void
     */
    public function setUidLocal($uidLocal)
    {
        $this->uidLocal = $uidLocal;
    }
}