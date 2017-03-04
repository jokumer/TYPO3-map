<?php
namespace Jokumer\Map\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class AbstractRepository
 *
 * @package TYPO3
 * @subpackage tx_map
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class AbstractRepository extends Repository
{
    /**
     * Initializes the repository.
     *
     * @return void
     */
    public function initializeObject()
    {
        /** @var $querySettings QuerySettingsInterface */
        $querySettings = $this->objectManager->get(QuerySettingsInterface::class);
        
        //@todo: #1365779762: Missing storage page ids. Find out why!
        $querySettings->setRespectStoragePage(true);
        $configurationManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class);
        $frameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $querySettings->setStoragePageIds(\TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $frameworkConfiguration['persistence']['storagePid']));
        
        $querySettings->setRespectSysLanguage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Override object type
     * Change default class for domain object
     * Normaly translateRepositoryNameToModelName(RepositoryClassName)
     *
     * @return void
     */
    public function overrideObjectType($objectTypeClass)
    {
        $this->objectType = $objectTypeClass;
        return $this;
    }

    /**
     * Returns all objects of this repository
     * Sets limit to avoid fatal error: Allowed memory size of x
     *
     * @return QueryResultInterface|array
     * @api
     */
    public function findAll($limit = 2000) {
        $query = $this->createQuery();
        return $query
            ->setLimit($limit)
            ->execute();
    }

    /**
     * Find all by categories
     *
     * @param string|null $categories
     * @param string|null $categoryFieldName
     * @return QueryResultInterface|array
     */
    public function findAllByCategories($categories = null, $categoryFieldName = null)
    {
        if ($categoryFieldName === null) {
            switch ($this->objectType) {
                case 'Jokumer\Map\Domain\Model\Category':
                    $categoryFieldName = 'uid';
                    break;
                default:
                    $categoryFieldName = 'category';
            }
        }
        $result = null;
        $allowedCategoryUids = $this->getAllowedFromRequestedCategoryUids($categories);
        if (is_array($allowedCategoryUids) && !empty($allowedCategoryUids)) {
            $query = $this->createQuery();
            $result = $query->matching($query->in($categoryFieldName, $allowedCategoryUids))->execute();
        }
        return $result;
    }

    /**
     * Get allowed from requested category uids
     *
     * @param string|array|null $requestedCategoryUids comma-separated list of requested category uids
     * @return array $allowedCategoryUids
     */
    protected function getAllowedFromRequestedCategoryUids($requestedCategoryUids = null) {
        $allowedCategoryUids = [];
        if ($requestedCategoryUids !== null) {
            if (!is_array($requestedCategoryUids)) {
                $requestedCategoryUids = GeneralUtility::trimExplode(',', $requestedCategoryUids);
            }
            if (is_array($requestedCategoryUids) && !empty($requestedCategoryUids)) {
                $availableCategoryUids = $this->getAvailableCategoryUids();
                if (!empty($availableCategoryUids)) {
                    $allowedCategoryUids = array_intersect($requestedCategoryUids, $availableCategoryUids);
                }
            }
        }
        return $allowedCategoryUids;
    }

    /**
     * Get available category uids
     *
     * @return array $availableCategoryUids
     */
    protected function getAvailableCategoryUids() {
        $availableCategoryUids = [];
        /** @var $categoryRepository CategoryRepository */
        $categoryRepository = $this->objectManager->get(CategoryRepository::class);
        $categoriesQueryResults = $categoryRepository->findAll();
        if (count($categoriesQueryResults)) {
            foreach ($categoriesQueryResults as $category) {
                $availableCategoryUids[] = $category->getUid();
            }
        }
        return $availableCategoryUids;
    }
}
