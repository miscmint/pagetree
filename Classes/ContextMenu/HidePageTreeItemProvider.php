<?php
declare(strict_types=1);

namespace Dsimon\Pagetree\ContextMenu;

use TYPO3\CMS\Backend\ContextMenu\ItemProviders\AbstractProvider;

/**
 * Item provider adding hide page tree item
 */
class HidePageTreeItemProvider extends AbstractProvider
{
    /**
     * This array contains configuration for items you want to add
     * @var array
     */
    protected $itemsConfiguration = [
        'hidePageTree' => [
            'type' => 'item',
            'label' => 'LLL:EXT:pagetree/Resources/Private/Language/Backend.xlf:labels.hide_whole_pagetree_from_here',
            'iconIdentifier' => 'pagetree-plugin',
            'callbackAction' => 'hidePageTree'
        ]
    ];

    /**
     * Checks if this provider may be called to provide the list of context menu items for given table.
     *
     * @return bool
     */
    public function canHandle(): bool
    {
        // Current table is: $this->table
        // Current UID is: $this->identifier
        return $this->table === 'pages';
    }

    /**
     * Returns the provider priority which is used for determining the order in which providers are processing items
     * to the result array. Highest priority means provider is evaluated first.
     *
     * @return int
     */
    public function getPriority(): int
    {

        return 90;
    }

    /**
     * @param string $itemName
     * @return array
     */
    protected function getAdditionalAttributes(string $itemName): array
    {
        return [
            'data-callback-module' => 'TYPO3/CMS/Pagetree/ContextMenuActions',
        ];
    }

    /**
     * This method adds custom item to list of items generated by item providers with higher priority value (PageProvider)
     * You could also modify existing items here.
     * The new item is added after the 'info' item.
     *
     * @param array $items
     * @return array
     */
    public function addItems(array $items): array
    {
        $currentBackendUserIsAdmin = $GLOBALS['BE_USER']->isAdmin() == 1 ? true : false;
        $hidePageTreeConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pagetree']['hidePageTree'];
        switch ($hidePageTreeConfiguration) {
            case 0: // none
                return $items;
            case 1: // only admins
                if (!$GLOBALS['BE_USER']->isAdmin()) {
                    return $items;
                }
                break;
            case 2: // only non-admins
                if ($GLOBALS['BE_USER']->isAdmin()) {
                    return $items;
                }
                break;
            default: // all
                // do nothing
                break;
        }
        $this->initDisabledItems();
        if (isset($items['info'])) {
            // renders an item based on the configuration from $this->itemsConfiguration
            $localItems = $this->prepareItems($this->itemsConfiguration);
            //finds a position of the item after which item should be added
            $position = array_search('info', array_keys($items), true);

            //slices array into two parts
            $beginning = array_slice($items, 0, $position+1, true);
            $end = array_slice($items, $position, null, true);

            // adds custom item in the correct position
            $items = $beginning + $localItems + $end;
        }
        //passes array of items to the next item provider
        return $items;
    }

    /**
     * This method is called for each item this provider adds and checks if given item can be added
     *
     * @param string $itemName
     * @param string $type
     * @return bool
     */
    protected function canRender(string $itemName, string $type): bool
    {
        return true;
    }
}
