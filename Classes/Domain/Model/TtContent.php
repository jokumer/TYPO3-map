<?php
namespace Jokumer\Map\Domain\Model;

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
class TtContent extends AbstractEntity
{
    /**
     * Alternative text
     *
     * @var string
     */
    protected $altText;

    /**
     * Bodytext
     *
     * @var string
     */
    protected $bodytext;

    /**
     * Columns
     *
     * @var int
     */
    protected $cols;

    /**
     * Column position
     *
     * @var int
     */
    protected $colPos;

    /**
     * Creation date
     *
     * @var \DateTime
     */
    protected $crdate;

    /**
     * Content type
     * 
     * @var string
     */
    protected $CType;

    /**
     * Header
     * 
     * @var string
     */
    protected $header;

    /**
     * Header layout
     *
     * @var string
     */
    protected $headerLayout;

    /**
     * Header link
     *
     * @var string
     */
    protected $headerLink;

    /**
     * Header position
     * 
     * @var string
     */
    protected $headerPosition;

    /**
     * Image
     * 
     * @var string
     */
    protected $image;

    /**
     * Image border
     *
     * @var int
     */
    protected $imageborder;

    /**
     * Image caption
     *
     * @var string
     */
    protected $imagecaption;

    /**
     * Image columns
     *
     * @var int
     */
    protected $imagecols;

    /**
     * Image link
     *
     * @var string
     */
    protected $imageLink;

    /**
     * Image orientation
     *
     * @var int
     */
    protected $imageorient;

    /**
     * Image width
     * 
     * @var int
     */
    protected $imagewidth;

    /**
     * Image zoom
     *
     * @var string
     */
    protected $imageZoom;

    /**
     * Layout
     * 
     * @var string
     */
    protected $layout;

    /**
     * List type
     *
     * @var string
     */
    protected $listType;

    /**
     * Media
     *
     * @var string
     */
    protected $media;

    /**
     * Subheader
     * 
     * @var string
     */
    protected $subheader;

    /**
     * Title text
     * 
     * @var string
     */
    protected $titleText;

    /**
     * Timestamp
     *
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * Get alternative text
     *
     * @return string
     */
    public function getAltText()
    {
        return $this->altText;
    }

    /**
     * Set alternative text
     *
     * @param $altText
     * @return void
     */
    public function setAltText($altText)
    {
        $this->altText = $altText;
    }

    /**
     * Get bodytext
     *
     * @return string
     */
    public function getBodytext()
    {
        return $this->bodytext;
    }

    /**
     * Set bodytext
     *
     * @param $bodytext
     * @return void
     */
    public function setBodytext($bodytext)
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Get column position
     *
     * @return int
     */
    public function getColPos()
    {
        return (int)$this->colPos;
    }

    /**
     * Set column position
     *
     * @param int $colPos
     * @return void
     */
    public function setColPos($colPos)
    {
        $this->colPos = $colPos;
    }

    /**
     * Get columns
     *
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * Set columns
     *
     * @param $cols
     * @return void
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    }

    /**
     * Get creation date
     * 
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set creation date
     * 
     * @param $crdate
     * @return void
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Get content type
     *
     * @return string
     */
    public function getCType()
    {
        return $this->CType;
    }

    /**
     * Set content type
     *
     * @param $ctype
     * @return void
     */
    public function setCType($ctype)
    {
        $this->CType = $ctype;
    }

    /**
     * Get header
     * 
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set header
     * 
     * @param $header
     * @return void
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * Get header layout
     *
     * @return string
     */
    public function getHeaderLayout()
    {
        return $this->headerLayout;
    }

    /**
     * Set header layout
     *
     * @param $headerLayout
     * @return void
     */
    public function setHeaderLayout($headerLayout)
    {
        $this->headerLayout = $headerLayout;
    }

    /**
     * Get header link
     *
     * @return string
     */
    public function getHeaderLink()
    {
        return $this->headerLink;
    }

    /**
     * Set header link
     *
     * @param $headerLink
     * @return void
     */
    public function setHeaderLink($headerLink)
    {
        $this->headerLink = $headerLink;
    }

    /**
     * Get header position
     * 
     * @return string
     */
    public function getHeaderPosition()
    {
        return $this->headerPosition;
    }

    /**
     * Set header position
     * 
     * @param $headerPosition
     * @return void
     */
    public function setHeaderPosition($headerPosition)
    {
        $this->headerPosition = $headerPosition;
    }

    /**
     * Get image
     * 
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     * 
     * @param $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get image border
     *
     * @return int
     */
    public function getImageborder()
    {
        return $this->imageborder;
    }

    /**
     * Set image border
     *
     * @param $imageborder
     * @return void
     */
    public function setImageborder($imageborder)
    {
        $this->imageborder = $imageborder;
    }

    /**
     * Get image caption
     *
     * @return string
     */
    public function getImagecaption()
    {
        return $this->imagecaption;
    }

    /**
     * Set image caption
     *
     * @param $imagecaption
     * @return void
     */
    public function setImagecaption($imagecaption)
    {
        $this->imagecaption = $imagecaption;
    }

    /**
     * Get image columns
     *
     * @return int
     */
    public function getImagecols()
    {
        return $this->imagecols;
    }

    /**
     * Set image columns
     *
     * @param $imagecols
     * @return void
     */
    public function setImagecols($imagecols)
    {
        $this->imagecols = $imagecols;
    }

    /**
     * Get image link
     *
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * Set image link
     *
     * @param $imageLink
     * @return void
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;
    }

    /**
     * Get image orientation
     *
     * @return int
     */
    public function getImageorient()
    {
        return $this->imageorient;
    }

    /**
     * Set image orientation
     *
     * @param $imageorient
     * @return void
     */
    public function setImageorient($imageorient)
    {
        $this->imageorient = $imageorient;
    }

    /**
     * Get image width
     *
     * @return int
     */
    public function getImagewidth()
    {
        return $this->imagewidth;
    }

    /**
     * Set image width
     *
     * @param $imagewidth
     * @return void
     */
    public function setImagewidth($imagewidth)
    {
        $this->imagewidth = $imagewidth;
    }

    /**
     * Get image zoom
     *
     * @return string
     */
    public function getImageZoom()
    {
        return $this->imageZoom;
    }

    /**
     * Set image zoom
     *
     * @param $imageZoom
     * @return void
     */
    public function setImageZoom($imageZoom)
    {
        $this->imageZoom = $imageZoom;
    }

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set layout
     *
     * @param $layout
     * @return void
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Get list type
     *
     * @return string
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * Set list type
     *
     * @param $listType
     * @return void
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    /**
     * Get media
     * 
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set media
     * 
     * @param $media
     * @return void
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Get subheader
     * 
     * @return string
     */
    public function getSubheader()
    {
        return $this->subheader;
    }

    /**
     * Set subheader
     * 
     * @param $subheader
     * @return void
     */
    public function setSubheader($subheader)
    {
        $this->subheader = $subheader;
    }

    /**
     * Get title text
     * 
     * @return string
     */
    public function getTitleText()
    {
        return $this->titleText;
    }

    /**
     * Set title text
     * 
     * @param $titleText
     * @return void
     */
    public function setTitleText($titleText)
    {
        $this->titleText = $titleText;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set timestamp
     *
     * @param $tstamp
     * @return void
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }
}
