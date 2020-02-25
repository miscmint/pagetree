/**
 * Module: TYPO3/CMS/Pagetree/ContextMenuActions
 *
 * @exports TYPO3/CMS/Pagetree/ContextMenuActions
 */
define(["require", "exports", "jquery"], function(require, exports, jquery) {
    'use strict';

    /**
     * @exports TYPO3/CMS/Pagetree/ContextMenuActions
     */
    var ContextMenuActions = {};

    /**
     * Say hello
     *
     * @param {string} table
     * @param {int} uid of the page
     */
    ContextMenuActions.hidePageTree = function (table, uid) {
        if (table === 'pages') {
            const page = jquery('#identifier-0_' + uid);
            page.find('.toggle').trigger('click');
        }

        jquery.ajax(TYPO3.settings.ajaxUrls.hide_whole_pagetree_from_here, {
            data: {
                pageUid: uid
            },
            method: "post"
        }).done(function() {
            jquery('.js-svg-refresh button').trigger('click');
        });
    };

    return ContextMenuActions;
});