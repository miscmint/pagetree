<?php
declare(strict_types=1);

namespace Dsimon\Pagetree\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Frontend\Page\PageRepository;
use TYPO3\CMS\Backend\Configuration\BackendUserConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageTreeController
{
    /**
     * @var PageRepository
     */
    protected $pageRepository;

    public function __construct()
    {
        $this->pageRepository = GeneralUtility::makeInstance(PageRepository::class);
    }

    /**
     * processAjaxRequest
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function processAjaxRequest(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getParsedBody() ?? $request->getQueryParams();
        $pageUid = $params['pageUid'];

        $pageAndSubpagesUids = $this->getPageAndSubpageUids((int)$pageUid);
        $backendUserConfiguration = GeneralUtility::makeInstance(BackendUserConfiguration::class);
        $backendUserConfigurationArray = $backendUserConfiguration->getAll();
        $stateHashes = $backendUserConfigurationArray['BackendComponents']['States']['Pagetree']['stateHash'];

        foreach ($stateHashes as $key => $hash) {
            foreach ($pageAndSubpagesUids as $pageUid) {
                $hashArray = explode('_', $key);
                if ($pageUid == $hashArray[1]) {
                    $backendUserConfiguration->set('BackendComponents.States.Pagetree.stateHash.' . $key, 0);
                }
            }
        }
        return (new JsonResponse())->setPayload();
    }

    /**
     * @var int uid
     * @return array
     */
    protected function getPageAndSubpageUids($uid)
    {
        $pages = [$uid];
        $subpages = $this->pageRepository->getMenu($uid, 'uid');
        
        foreach ($subpages as $subpage) {
            $pages = array_merge($pages, $this->getPageAndSubpageUids($subpage['uid']));
        }
        return $pages;
    }

    /**
     * @param PageRepository $pageRepository
     */
    public function injectPageRepository(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
}
