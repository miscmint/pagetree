/**
 * Module: TYPO3/CMS/Pagetree/ContextMenuActions
 *
 * @exports TYPO3/CMS/Pagetree/ContextMenuActions
 */
define(['jquery'], function($) {
    'use strict';

    /**
     * @exports TYPO3/CMS/Pagetree/ContextMenuActions
     */
    var ContextMenuActions = {};

    /**
     *
     * @param {string} table
     * @param {int} uid of the page
     */
    ContextMenuActions.hidePageTree = function (table, uid) {
        if (table === 'pages') {
            const page = $('#identifier-0_' + uid);
            page.find('.toggle').trigger('click');
        }

        $.ajax(TYPO3.settings.ajaxUrls.hide_whole_pagetree_from_here, {
            data: {
                pageUid: uid
            },
            method: "post"
        }).done(function() {
            $('.js-svg-refresh button').trigger('click');
        });
    };

    return ContextMenuActions;
});