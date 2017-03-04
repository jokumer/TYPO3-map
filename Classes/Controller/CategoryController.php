<?php
namespace Jokumer\Map\Controller;

use Jokumer\Map\Controller\AbstractController;
use Jokumer\Map\Domain\Model\Category;
use Jokumer\Map\Domain\Repository\CategoryRepository;

/**
 * Class CategoryController
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CategoryController extends AbstractController
{
    /**
     * Action list
     *
     * @return void
     */
    public function listAction()
    {
        $categories = $this->categoryRepository->findAll();
        $this->view->assign('categories', $categories);
        if ($this->view instanceof \TYPO3\CMS\Extbase\Mvc\View\JsonView) {
            $this->view->setVariablesToRender(array('categories'));
        }
    }

    /**
     * Action show
     *
     * @param Category $category
     * @return void
     */
    public function showAction(Category $category)
    {
        $this->view->assign('category', $category);
        if ($this->view instanceof \TYPO3\CMS\Extbase\Mvc\View\JsonView) {
            $this->view->setVariablesToRender(array('category'));
        }
    }
}
