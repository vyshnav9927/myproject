//
// [START] Core file
//
(function($) {
    $.fn.wpAssetCleanUp = function() {
        var metaBoxContent = '#wpacu_meta_box_content';

        return {
            cssJsManagerActions: function () {
                var cbSelector = '.input-unload-on-this-page',
                    cbSelectorNotLocked = '.input-unload-on-this-page.wpacu-not-locked',
                    cbSelectorMakeExceptionOnPage = '.wpacu_load_it_option_one.wpacu_load_exception',
                    handle, handleFor, $targetedAssetRow;

                // live() is deprecated and if used and jQuery Migrate is disabled
                // it could break the website's front-end functionality
                $(document).on('click change', cbSelector, function (event) {
                    handle = $(this).attr('data-handle');
                    handleFor = $(this).hasClass('wpacu_unload_rule_for_style') ? 'style' : 'script';

                    if ($(this).prop('checked')) {
                        if (event.type === 'click' && (! $.fn.wpAssetCleanUp().triggerAlertWhenAnyUnloadRuleIsChosen(handle, handleFor))) {
                            return false;
                        }

                        $.fn.wpAssetCleanUp().uncheckAllOtherBulkUnloadRules($(this), false); // skip Unload via RegEx as both can be used

                        // Show load exceptions area (for exceptions like load it if the user is logged in)
                        $.fn.wpAssetCleanUp().showHandleLoadExceptionArea(handleFor, handle);
                        $(this).closest('tr').addClass('wpacu_not_load');
                    } else {
                        $(this).closest('tr').removeClass('wpacu_not_load');
                        $targetedAssetRow = $(this).parents('.wpacu_asset_row');
                        $.fn.wpAssetCleanUp().hideHandleLoadExceptionArea($targetedAssetRow, handle, handleFor);
                    }
                });

                /*
                 * [Start] Unload on this page
                 */
                // Check All
                $('.wpacu-plugin-check-all').on('click', function (e) {
                    e.preventDefault();

                    var wpacuPluginTarget = $(this).attr('data-wpacu-plugin');
                    //console.log(wpacuPluginTarget);

                    $('table.wpacu_list_by_location[data-wpacu-plugin="' + wpacuPluginTarget + '"]')
                        .find(cbSelectorNotLocked)
                        .prop('checked', true).closest('tr').addClass('wpacu_not_load');
                });

                // Uncheck All
                $('.wpacu-plugin-uncheck-all').on('click', function (e) {
                    e.preventDefault();

                    var wpacuPluginTarget = $(this).attr('data-wpacu-plugin');

                    $('table.wpacu_list_by_location[data-wpacu-plugin="' + wpacuPluginTarget + '"]')
                        .find(cbSelectorNotLocked)
                        .prop('checked', false).closest('tr').removeClass('wpacu_not_load');
                });
                /*
                 * [End] Unload on this page
                 */

                /*
                * [Start] Make exception, Load it on this page
                */
                // Check All
                $('.wpacu-plugin-check-load-all').on('click change', function (e) {
                    e.preventDefault();

                    var wpacuPluginTarget = $(this).attr('data-wpacu-plugin');
                    var $wpacuPluginList = $('table.wpacu_list_by_location[data-wpacu-plugin="' + wpacuPluginTarget + '"]');

                    $wpacuPluginList
                        .find(cbSelectorMakeExceptionOnPage)
                        .prop('checked', true).closest('tr.wpacu_is_bulk_unloaded').removeClass('wpacu_not_load');

                    $wpacuPluginList.find(cbSelectorNotLocked).prop('checked', false).trigger('change');
                });

                // Uncheck All
                $('.wpacu-plugin-uncheck-load-all').on('click change', function (e) {
                    e.preventDefault();

                    var wpacuPluginTarget = $(this).attr('data-wpacu-plugin');
                    var $wpacuPluginList = $('table.wpacu_list_by_location[data-wpacu-plugin="' + wpacuPluginTarget + '"]');

                    $wpacuPluginList
                        .find(cbSelectorMakeExceptionOnPage)
                        .prop('checked', false).closest('tr.wpacu_is_bulk_unloaded').addClass('wpacu_not_load');

                    $wpacuPluginList.find(cbSelectorNotLocked).prop('checked', false).trigger('change');
                });
                /*
                * [End] Make exception, Load it on this page
                */

                $(document).on('click', '.wpacu_keep_bulk_rule', function () {
                    if ($(this).prop('checked')) {
                        $(this).parents('li').next().removeClass('remove_rule');
                    }
                });

                $(document).on('click', '.wpacu_remove_bulk_rule', function () {
                    if ($(this).prop('checked')) {
                        $(this).parents('li').addClass('remove_rule');
                    }
                });

                // Unload on All Pages of post/page/custom post type / site-wide (everywhere) / based on taxonomy
                $(document).on('click change', '.wpacu_bulk_unload', function (event) {
                    var $mainThis = $(this);
                    handle = $(this).attr('data-handle');
                    handleFor = $(this).attr('data-handle-for'); // 'style' or 'script' (e.g. 'contact-form-7' has the same name for both)
                    $targetedAssetRow = $('[data-' + handleFor + '-handle-row="' + handle + '"]');

                    var $parentLi = $(this).parents('li');

                    /**************************************************************
                     * STATE 1: The checkbox IS CHECKED (show multiple drop-down)
                     * ************************************************************
                     */
                    if ($(this).prop('checked')) {
                        if (event.type === 'click' && (! $.fn.wpAssetCleanUp().triggerAlertWhenAnyUnloadRuleIsChosen(handle, handleFor))) {
                            return false;
                        }

                        if ($(this).hasClass('wpacu_global_unload') || $(this).hasClass('wpacu_post_type_unload')) {
                            /*
                             * Clicked: "Unload site-wide" (.wpacu_global_unload) or "Unload on all posts of the same [post_type]" (.wpacu_post_type_unload)
                             */
                            $(this).parent('label').addClass('wpacu_input_load_checked');
                            $(this).closest('tr').addClass('wpacu_not_load');
                        }

                        // Show load exceptions area if Unload everywhere or other bulk unload rule is chosen
                        $.fn.wpAssetCleanUp().showHandleLoadExceptionArea(handleFor, handle);

                        if ($(this).hasClass('wpacu_global_unload')) {
                            // CSS/JS: Unload Site-Wide (Everywhere) was clicked
                            $.fn.wpAssetCleanUp().uncheckAllOtherBulkUnloadRules($(this), true);

                            // Obviously, "Unload on this page" should be unchecked as the rule overwrites it
                            $('.input-unload-on-this-page[data-handle-for="' + handleFor + '"][data-handle="' + handle + '"]')
                                .prop('checked', false);

                        } else if ($(this).hasClass('wpacu_post_type_unload')) {
                            // Unload on All Pages of "[post_type_here]" post type
                            $.fn.wpAssetCleanUp().uncheckAllOtherBulkUnloadRules($(this), false);

                            // Obviously, "Unload on this page" should be unchecked as the rule overwrites it
                            $('.input-unload-on-this-page[data-handle-for="' + handleFor + '"][data-handle="' + handle + '"]')
                                .prop('checked', false);
                        }
                        //wpAssetCleanUp.uncheckAllOtherBulkUnloadRules($(this));
                        //$(this).closest('tr').find('.wpacu_remove_site_wide_rule').prop('checked', true);
                    } else {
                        /***********************************************************************************
                         * STATE 2: The checkbox IS UNCHECKED / UNMARKED (the multiple drop-down is hidden)
                         ***********************************************************************************
                         */
                        if (!$(this).hasClass('wpacu_unload_it_regex_checkbox') && !$(this).hasClass('wpacu_unload_it_via_tax_checkbox')) {
                            /*
                             * Clicked: "Unload site-wide" or "Unload on all posts of the same [post_type]"
                             */
                            $(this).parent('label').removeClass('wpacu_input_load_checked');
                            $(this).closest('tr').removeClass('wpacu_not_load');
                        } else if ($(this).hasClass('wpacu_unload_it_regex_checkbox')) {
                            /*
                             * "Unload via RegEx" is clicked
                             */
                            $parentLi.find('label').removeClass('wpacu_unload_checked');
                            $parentLi.find('textarea')
                                .blur() // lose focus
                                .addClass('wpacu_disabled');

                            // Action taken if the input has no value
                            if ($parentLi.find('textarea').val().trim() === '') {
                                $parentLi.find('textarea')
                                    .prop('disabled', true).val(''); // unchecked with no value added to the input

                                $parentLi.find('.wpacu_handle_unload_regex_input_wrap')
                                    .addClass('wpacu_hide'); // Hide the input area
                            }
                        } else if ($(this).hasClass('wpacu_unload_it_via_tax_checkbox')) {
                            /*
                             * "Unload via taxonomy" is clicked
                             */
                            $parentLi.find('label').removeClass('wpacu_unload_checked');
                            /*
                            $parentLi.find('select')
                                .blur() // lose focus
                                .addClass('wpacu_disabled');
                            */
                            //$parentLi.find('select').prop('disabled', true).val(''); // unchecked with no value added to the input
                            $parentLi.find('.wpacu_handle_unload_via_tax_input_wrap').addClass('wpacu_hide'); // Hide the input area
                        }

                        // [wpacu_lite]
                        // If it's NOT already unloaded (on page load)
                        // All bulk unloads are unchecked
                        // Then HIDE make exceptions area
                        $.fn.wpAssetCleanUp().hideHandleLoadExceptionArea($targetedAssetRow, handle, handleFor);
                        // [/wpacu_lite]
                    }

                    // No bulk rule already applied (red background) and none of the bulk unloads (except RegEx) checkboxes are checked
                    if (!$targetedAssetRow.hasClass('wpacu_is_bulk_unloaded') && !$('.wpacu_bulk_unload:not(.wpacu_unload_it_regex_checkbox)').is(':checked')) {
                        $(this).closest('tr').removeClass('wpacu_not_load');
                    }
                });

                // Load it on this page
                $(document).on(
                    'click change', // when these actions are taken
                    cbSelectorMakeExceptionOnPage + ',' + '.wpacu_load_it_option_post_type', // on these elements
                    function () { // trigger the following function
                        var handle = $(this).attr('data-handle');

                        if ($(this).prop('checked')) {
                            $(this).parent('label').addClass('wpacu_global_unload_exception');

                            // Uncheck "Unload on this page" as it's not relevant anymore
                            var asset_type = '';

                            if ($(this).hasClass('wpacu_style')) {
                                asset_type = 'style';
                            } else if ($(this).hasClass('wpacu_script')) {
                                asset_type = 'script';
                            }

                            $('#' + asset_type + '_' + handle).prop('checked', false).trigger('change');
                        } else {
                            $(this).parent('label').removeClass('wpacu_global_unload_exception');
                        }
                    }
                );

                // Handle Notes
                $(document).on('click', '.wpacu-add-handle-note', function (e) {
                    e.preventDefault();

                    var wpacuHandle = $(this).attr('data-handle'), $wpacuNotesFieldArea, $wpacuNoteInput;

                    if ($(this).hasClass('wpacu-for-script')) {
                        $wpacuNotesFieldArea = $('.wpacu-handle-notes-field[data-script-handle="' + wpacuHandle + '"]');
                    } else if ($(this).hasClass('wpacu-for-style')) {
                        $wpacuNotesFieldArea = $('.wpacu-handle-notes-field[data-style-handle="' + wpacuHandle + '"]');
                    }

                    if ($wpacuNotesFieldArea.length < 1) {
                        return;
                    }

                    $wpacuNoteInput = $wpacuNotesFieldArea.find(':input');

                    if ($wpacuNotesFieldArea.is(':hidden')) {
                        // When "Add Note" is clicked, mark the textarea as visible and not disabled
                        $wpacuNotesFieldArea.show();
                        $wpacuNoteInput.prop('disabled', false);
                    } else {
                        $wpacuNotesFieldArea.hide();

                        // Was the area hidden without any textarea value and the value was null on page load?
                        // Mark it as disabled (save total sent inputs for PHP processing)
                        // If there's ONLY space added (could be by mistake) to the textarea, ignore it as it's irrelevant
                        if ($wpacuNoteInput.val().trim() === '' && $wpacuNoteInput.attr('data-wpacu-is-empty-on-page-load') === 'true') {
                            $wpacuNoteInput.prop('disabled', true).val('');
                        }
                    }
                });

                // [Get external asset size]
                $(document).on('click', '.wpacu-external-file-size', function (e) {
                    e.preventDefault();

                    var $wpacuCurrentTarget = $(this),
                        $wpacuFileSizeArea,
                        wpacuRemoteFile = $wpacuCurrentTarget.attr('data-src');

                    $wpacuCurrentTarget.hide();

                    $wpacuFileSizeArea = $wpacuCurrentTarget.next();
                    $wpacuFileSizeArea.show();

                    if (wpacuRemoteFile.includes('/?')) { // Dynamic CSS/JS
                        $.get(wpacuRemoteFile, {}, function (output, textStatus, request) {
                            if (textStatus !== 'success') {
                                return 'N/A';
                            }

                            $wpacuFileSizeArea.html($.fn.wpAssetCleanUp().wpacuBytesToSize(output.length));
                        });
                    } else {
                        $.post(wpacu_object.ajax_url, {
                            'action':             wpacu_object.plugin_prefix + '_get_external_file_size',
                            'wpacu_remote_file':  wpacuRemoteFile,
                            'wpacu_nonce':        wpacu_object.wpacu_ajax_check_remote_file_size_nonce
                        }, function (size) {
                            $wpacuFileSizeArea.html(size);
                        });
                    }
                });
                // [/Get external asset size]

                // Note: Starting from July 24, 2021, development has started to use AJAX to save the state
                $(document).on('click', '.wpacu_handle_row_expand_contract', function (e) {
                    e.preventDefault();

                    var wpacuAssetHandle = $(this).attr('data-wpacu-handle'),
                        wpacuAssetHandleFor = $(this).attr('data-wpacu-handle-for'),
                        wpacuNewAssetRowState;

                    if ($(this).find('span').hasClass('dashicons-minus')) {
                        /*
                         * Already expanded when clicked (had minus sign)
                         */
                        wpacuNewAssetRowState = 'contracted';

                        $(this).parents('td').attr('data-wpacu-row-status', wpacuNewAssetRowState)
                            .find('.wpacu_handle_row_expanded_area').addClass('wpacu_hide');
                        $(this).find('span').removeClass('dashicons-minus').addClass('dashicons-plus');

                        } else if ($(this).find('span').hasClass('dashicons-plus')) {
                        /*
                         * Already contracted when clicked (had plus sign)
                         */
                        wpacuNewAssetRowState = 'expanded';

                        $(this).parents('td').attr('data-wpacu-row-status', wpacuNewAssetRowState).find('.wpacu_handle_row_expanded_area').removeClass('wpacu_hide');
                        $(this).find('span').removeClass('dashicons-plus').addClass('dashicons-minus');

                        }

                    $.fn.wpAssetCleanUp().wpacuAjaxUpdateKeepTheAssetRowState(wpacuNewAssetRowState, wpacuAssetHandle, wpacuAssetHandleFor, $(this));
                });
            },

            triggerAlertWhenAnyUnloadRuleIsChosen: function (handle, handleFor) {
                // The moment the load exception area is shown, it means at least one unload rule was set
                // There are cases when the admin needs to be alerted

                // Dashicons
                if (handle === 'dashicons' && handleFor === 'style') {
                    if ($('input[name="wpacu_ignore_child[styles][nf-display]').length > 0 && !confirm(wpacu_object.dashicons_unload_alert_ninja_forms)) {
                        return false;
                    }
                }

                if (handleFor === 'script') {
                    // jQuery library
                    if ((handle === 'jquery' || handle === 'jquery-core')) {
                        if ($('#script_jquery_ignore_children').length > 0 && !confirm(wpacu_object.jquery_unload_alert)) {
                            return false;
                        }
                    }

                    // JavaScript Cookie (https://github.com/js-cookie/js-cookie)
                    // Parent of: wc-cart-fragments, woocommerce
                    if (handle === 'js-cookie') {
                        if (!confirm(wpacu_object.woo_js_cookie_unload_alert)) {
                            return false;
                        }
                    }

                    // WooCommerce's "wc-cart-fragments" JS file
                    if (handle === 'wc-cart-fragments') {
                        if (!confirm(wpacu_object.woo_wc_cart_fragments_unload_alert)) {
                            return false;
                        }
                    }

                    // Other JS files
                    if ((handle === 'backbone' || handle === 'underscore')) {
                        if (!confirm(wpacu_object.sensitive_library_unload_alert)) {
                            return false;
                        }
                    }
                }

                return true;
            },

            showHandleLoadExceptionArea: function (handleFor, handle) {
                //console.log('div.wpacu_exception_options_area_wrap[data-'+ handleFor +'-handle="'+ handle +'"]');
                var $targetedLoadExceptionArea = $('div.wpacu_exception_options_area_wrap[data-' + handleFor + '-handle="' + handle + '"]');
                $targetedLoadExceptionArea.parent('div').removeClass('wpacu_hide');
                // Remove "disabled" attribute to any load exceptions checkboxes
                // Except the locked ones if the Lite version is used
                $targetedLoadExceptionArea.find('input[type="checkbox"]').not('.wpacu_lite_locked').prop('disabled', false);
            },
            hideHandleLoadExceptionArea: function ($targetedAssetRow, handle, handleFor) {
                // If it's NOT already unloaded (on page load)
                // All bulk unloads are unchecked
                // Then HIDE make exceptions area
                if (!$targetedAssetRow.hasClass('wpacu_is_bulk_unloaded')) {
                    if (!$targetedAssetRow.find('.wpacu_bulk_unload').is(':checked')) {
                        var $targetedLoadExceptionArea = $('div.wpacu_exception_options_area_wrap[data-' + handleFor + '-handle="' + handle + '"]');
                        $targetedLoadExceptionArea.parent('div').addClass('wpacu_hide');
                        // Set "disabled" attribute any load exceptions checkboxes as they are irrelevant in this instance
                        $targetedLoadExceptionArea.find('input[type="checkbox"]').prop('disabled', true);
                    }
                }
            },

            uncheckAllOtherBulkUnloadRules: function ($targetInput, includingUnloadViaRegEx) {
                //console.log($targetInput.closest('tr').find('.wpacu_unload_rule_input'));
                var wpacuToFind = '.wpacu_bulk_unload';

                if (includingUnloadViaRegEx === false) {
                    wpacuToFind = '.wpacu_bulk_unload:not(.wpacu_unload_it_regex_checkbox)';
                }

                $targetInput.closest('tr').find(wpacuToFind).not($targetInput) // all except the target one
                    // uncheck it
                    .prop('checked', false)
                    // remove the "checked" style from the label
                    .parent('label').removeClass('wpacu_input_load_checked')
                    .removeClass('wpacu_unload_checked');
            },

            limitSubmittedFields: function () {
                var wpacuSubmitForm = false,
                    preloadTargetInput = '[data-wpacu-input="preload"]',
                    wpacuListToCheck = [];

                // Edit post/page area (e.g. /wp-admin/post.php?post=[POST_ID_HERE]&action=edit)
                // OR edit taxonomy area (e.g. /wp-admin/term.php?taxonomy=category&tag_ID=63&post_type=post)
                if ($('body.wp-admin form#post').length > 0 || $('body.wp-admin form#edittag').length > 0) {
                    if ($('#wpacu_unload_assets_area_loaded').length < 1) {
                        return true; // the CSS/JS area is not loaded on edit post/page area, thus no reason to continue
                    }

                    wpacuSubmitForm = true; // leave it always to true as the edit post/page/taxonomy form needs to always submit (might be edited later on)
                }

                if ($(preloadTargetInput).length > 0) {
                    wpacuListToCheck.push(preloadTargetInput);
                }

                if (wpacuListToCheck.length > 0) {
                    //console.log(wpacuListToCheck.join());
                    $(wpacuListToCheck.join()).each(function () {
                        //console.log($(this).val());
                        if (!$(this).val()) {
                            $(this).prop('disabled', 'disabled');
                        }
                    }).promise().done(function () {
                        wpacuSubmitForm = true;
                    });
                } else {
                    wpacuSubmitForm = true; // "Do not load Asset CleanUp Pro on this page (this will disable any functionality of the plugin)" could be enabled
                }

                return wpacuSubmitForm;
            },

            wpacuParseContentsForDirectCall: function (contents, statusCode) {
                if (contents.lastIndexOf(wpacu_object.start_del_e) < 0
                    || contents.lastIndexOf(wpacu_object.end_del_e) < 0
                    || contents.lastIndexOf(wpacu_object.start_del_h) < 0
                    || contents.lastIndexOf(wpacu_object.end_del_h) < 0
                ) {
                    // Sometimes, 200 OK (success) is returned, but due to an issue with the page, the assets list is not retrieved
                    // Do further checks if any of the markers are missing (even if there are no assets to manage, they should be printed)
                    var wpacuOutputError = wpacu_object.ajax_direct_fetch_error_with_success_response;

                    // Strip tags (Source: https://css-tricks.com/snippets/javascript/strip-html-tags-in-javascript/)
                    wpacuOutputError = wpacuOutputError.replace(
                        /{wpacu_output}/,
                        xhr.responseText.replace(/(<([^>]+)>)/ig, '')
                    );

                    // htmlEntities() PHP equivalent: https://css-tricks.com/snippets/javascript/htmlentities-for-javascript/
                    try {
                        wpacuOutputError = String(wpacuOutputError).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                    } catch (e) {
                        console.log(e);
                    }

                    $(metaBoxContent).html(wpacuOutputError);
                    return;
                }

                var wpacuListE = contents.substring(
                    (contents.lastIndexOf(wpacu_object.start_del_e) + wpacu_object.start_del_e.length),
                    contents.lastIndexOf(wpacu_object.end_del_e)
                );

                /*
                 * IMPORTANT NOTE: It looks like UglifyJS jas issues preserving comments that are after consecutive "var"
                 */
                var wpacuListH = contents.substring(
                    (contents.lastIndexOf(wpacu_object.start_del_h) + wpacu_object.start_del_h.length),
                    contents.lastIndexOf(wpacu_object.end_del_h)
                );

                var dataGetLoadedAssets = {
                    'action'            : wpacu_object.plugin_prefix + '_get_loaded_assets',
                    'wpacu_list_e'      : wpacuListE,
                    'wpacu_list_h'      : wpacuListH,
                    'post_id'           : wpacu_object.post_id,
                    'page_url'          : wpacu_object.page_url,
                    'tag_id'            : wpacu_object.tag_id,
                    'wpacu_taxonomy'    : wpacu_object.wpacu_taxonomy,
                    'force_manage_dash' : wpacu_object.force_manage_dash,
                    'is_for_singular'   : false, // e.g. Post ID, Post Title
                    'wpacu_nonce'       : wpacu_object.wpacu_ajax_get_loaded_assets_nonce,
                    'time_r'            : new Date().getTime()
                };

                if ($('#wpacu_manage_singular_page_assets').length > 0) { // e.g. /wp-admin/admin.php?page=wpassetcleanup_assets_manager
                    dataGetLoadedAssets['is_for_singular'] = true;
                }

                $.post(wpacu_object.ajax_url, dataGetLoadedAssets, function (response) {
                    if (!response) {
                        return;
                    }

                    $(metaBoxContent).html(response);

                    if (statusCode === 404) {
                        $(metaBoxContent).prepend('<p><span class="dashicons dashicons-warning"></span> ' + wpacu_object.server_returned_404_not_found + '</p><hr />');
                    }

                    if ($('#wpacu_dash_assets_manager_form').length > 0) {
                        $('#submit').show();
                    }

                    setTimeout(function () {
                        $.fn.wpAssetCleanUp().cssJsManagerActions();
                        $('.wpacu_asset_row, .wpacu-page-options .wpacu-assets-collapsible-content').removeClass('wpacu-loading'); // hide loading spinner after post is updated
                        $('#wpacu-assets-reloading').remove();

                        $.fn.wpAssetCleanUp().wpacuCheckSourcesFor404Errors();
                    }, 200);
                });
            },

            wpacuAjaxGetAssetsArea: function (forceFetch) {
                // Do not make any AJAX call unless force fetch is enabled
                if (!forceFetch && !$('#wpacu_ajax_fetch_assets_list_dashboard_view').length) {
                    return false;
                }

                // Was "Do not load Asset CleanUp Pro on this page (this will disable any functionality of the plugin)" ticked?
                // Do not load any list! Instead, make an AJAX call to load the restricted area mentioning that the restriction took effect

                var pageOptionNoPluginLoadTarget = '#wpacu_page_options_no_wpacu_load';
                if ($(pageOptionNoPluginLoadTarget).length > 0 && $(pageOptionNoPluginLoadTarget).prop('checked')) {
                    var dataLoadPageRestrictedArea = {
                        'action'      : wpacu_object.plugin_prefix + '_load_page_restricted_area',
                        'post_id'     : wpacu_object.post_id,
                        'wpacu_nonce' : wpacu_object.wpacu_ajax_load_page_restricted_area_nonce,
                        'time_r'      : new Date().getTime()
                    };

                    $.post(wpacu_object.ajax_url, dataLoadPageRestrictedArea, function (response) {
                        if (!response) {
                            return false;
                        }

                        $(metaBoxContent).html(response);

                        $('.wpacu_asset_row, .wpacu-page-options .wpacu-assets-collapsible-content').removeClass('wpacu-loading'); // hide loading spinner after post is updated
                        $('#wpacu-assets-reloading').remove();
                    });

                    return;
                }

                var dataDirect = {};

                if (wpacu_object.dom_get_type === 'direct') {
                    dataDirect[wpacu_object.plugin_prefix + '_load']   = 1;
                    dataDirect[wpacu_object.plugin_prefix + '_time_r'] = new Date().getTime();

                    $.ajax({
                        method: 'GET',
                        url: wpacu_object.page_url,
                        data: dataDirect,
                        cache: false,
                        complete: function (xhr, textStatus) {
                            if (xhr.statusText === 'error') {
                                // Make exception for 404 errors as there could be plugin used such as "404page – your smart custom 404 error page"
                                if (xhr.status === 404) {
                                    $.fn.wpAssetCleanUp().wpacuParseContentsForDirectCall(xhr.responseText, xhr.status, $);
                                    return;
                                }

                                // Strip any tags (Source: https://css-tricks.com/snippets/javascript/strip-html-tags-in-javascript/)
                                var errorTextOutput = xhr.responseText.replace(/(<([^>]+)>)/ig, '');

                                // htmlEntities() PHP equivalent: https://css-tricks.com/snippets/javascript/htmlentities-for-javascript/
                                try {
                                    errorTextOutput = String(errorTextOutput).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                                } catch (e) {
                                    console.log(e);
                                }

                                var wpacuOutputError = wpacu_object.ajax_direct_fetch_error;
                                    wpacuOutputError = wpacuOutputError.replace(/{wpacu_output}/, errorTextOutput);
                                    wpacuOutputError = wpacuOutputError.replace(/{wpacu_status_code_error}/, xhr.status);

                                $(metaBoxContent).html(wpacuOutputError);
                            }
                        }
                    }).done(function (contents, _textStatus, jqXHR) {
                        //console.log(jqXHR);
                        //console.log(jqXHR.getAllResponseHeaders());

                        // "Step 1" (Fetch the assets from the home page) is now completed
                        $('#wpacu-fetch-list-step-1-wrap').addClass('wpacu-completed');
                        $('#wpacu-fetch-list-step-1-status').html($('#wpacu-list-step-completed-status').html());

                        // "Step 2" is in progress, mark it as such
                        $('#wpacu-fetch-list-step-2-status').html($('#wpacu-list-step-default-status').html());
                        $.fn.wpAssetCleanUp().wpacuParseContentsForDirectCall(contents);
                    });
                } else if (wpacu_object.dom_get_type === 'wp_remote_post') {
                    var dataGetLoadedAssets = {
                        'action':             wpacu_object.plugin_prefix + '_get_loaded_assets',
                        'post_id':            wpacu_object.post_id,
                        'page_url':           wpacu_object.page_url,
                        'tag_id':             wpacu_object.tag_id,
                        'wpacu_taxonomy':     wpacu_object.wpacu_taxonomy,
                        'force_manage_dash':  wpacu_object.force_manage_dash,
                        'wpacu_nonce':        wpacu_object.wpacu_ajax_get_loaded_assets_nonce,
                        'time_r':             new Date().getTime()
                    };

                    $.post(wpacu_object.ajax_url, dataGetLoadedAssets, function (response) {
                        if (!response) {
                            return false;
                        }

                        $(metaBoxContent).html(response);

                        if ($('#wpacu_dash_assets_manager_form').length > 0) {
                            $('#submit').show();
                        }

                        setTimeout(function () {
                            $.fn.wpAssetCleanUp().cssJsManagerActions();

                            setTimeout(function () {
                                $.fn.wpAssetCleanUp().wpacuCheckSourcesFor404Errors();
                            }, 100);
                        }, 200);
                    });
                }
            },

            wpacuParseResultsForHarcodedAssets: function (contents) {
                if (contents.lastIndexOf(wpacu_object.start_del_h) < 0 || contents.lastIndexOf(wpacu_object.end_del_h) < 0) {
                    // error in fetching the list
                }

                // IMPORTANT NOTE: It looks like UglifyJS has issues preserving comments that are after consecutive "var"
                var wpacuListH = contents.substring(
                    (contents.lastIndexOf(wpacu_object.start_del_h) + wpacu_object.start_del_h.length),
                    contents.lastIndexOf(wpacu_object.end_del_h)
                );

                var wpacuSettings = $('#wpacu-assets-collapsible-wrap-hardcoded-list').attr('data-wpacu-settings-frontend');

                var dataGetLoadedHardcodedAssets = {
                    'action'          : wpacu_object.plugin_prefix + '_print_loaded_hardcoded_assets',
                    'wpacu_list_h'    : wpacuListH,
                    'wpacu_settings'  : wpacuSettings, // includes $data values as well (with rules) to pass to the hardcoded list
                    'time_r'          : new Date().getTime(),
                    'wpacu_nonce'     : wpacu_object.wpacu_print_loaded_hardcoded_assets_nonce
                };

                $.post(wpacu_object.ajax_url, dataGetLoadedHardcodedAssets, function (response) {
                    var $mainJQuerySelector = '#wpacu-assets-collapsible-wrap-hardcoded-list';

                    if (!response) {
                        return;
                    }

                    if (response.includes('The security nonce is not valid')) {
                        $($mainJQuerySelector).find('> .wpacu-assets-collapsible-content').html(response);
                        return;
                    }

                    var responseJson = JSON.parse(response);

                    $($mainJQuerySelector).find('> .wpacu-assets-collapsible-content').html(responseJson.output);
                    $($mainJQuerySelector).find('a.wpacu-assets-collapsible')
                        .append(' &#10141; Total: ' + parseInt(responseJson.total_hardcoded_assets));
                });
            },

            wpacuCheckSourcesFor404Errors: function() {
                // Trigger on page load (front-end view)
                var $targetSources = $('[data-wpacu-external-source]');

                if ($targetSources.length < 1) {
                    return;
                }

                var totalExternalSources = $targetSources.length, checkUrlsToPass = '';

                $targetSources.each(function(wpacuIndex) {
                    var $targetSource = $(this), sourceUrl = $targetSource.attr('data-wpacu-external-source');

                    checkUrlsToPass += sourceUrl + '-at-wpacu-at-';

                    if (wpacuIndex === totalExternalSources - 1) {
                        $.post(wpacu_object.ajax_url, {
                            'action'            : wpacu_object.plugin_prefix + '_check_external_urls_for_status_code',
                            'wpacu_check_urls'  : checkUrlsToPass,
                            'wpacu_nonce'       : wpacu_object.wpacu_ajax_check_external_urls_nonce
                        }, function(response) {
                            var urlsList = $.parseJSON(response);

                            $.each(urlsList, function(index, sourceToHi) {
                                $('[data-wpacu-external-source="'+ sourceToHi +'"]')
                                    .css({'color': '#cc0000'})
                                    .parent('div')
                                    .find('[data-wpacu-external-source-status]')
                                    .html('<small>* <em style="font-weight: 600;">' + wpacu_object.source_load_error_msg + '</em></small>');
                            });
                        });
                    }
                    });
            },

            wpacuBytesToSize: function(bytes) {
                /**
                 * Inspired from: https://web.archive.org/web/20120507054320/http://codeaid.net/javascript/convert-size-in-bytes-to-human-readable-format-(javascript)
                 * Bytes to KB
                 */
                if (bytes === 0) {
                    return 'N/A';
                }

                return (bytes / 1024).toFixed(4) + ' KB';
            },

            wpacuAjaxUpdateKeepTheGroupsState: function(newState, btnIdClicked) {
                // Don't use resources and perform the AJAX call if the same "state" button is clicked
                var dataCurrentState = $('#wpacu-assets-groups-change-state-area').attr('data-wpacu-groups-current-state');

                if (dataCurrentState == newState) {
                    $('#' + btnIdClicked).prop('disabled', false); // Don't leave the button disabled
                    return;
                }

                var dataUpdateSetting = {
                    'action'                       : wpacu_object.plugin_prefix + '_update_settings',
                    'wpacu_nonce'                  : wpacu_object.wpacu_update_specific_settings_nonce,
                    'wpacu_update_keep_the_groups' : 'yes',
                    'wpacu_keep_the_groups_state'  : newState, // "expanded" or "contracted"
                    'time_r'                       : new Date().getTime() // avoid any caching
                };

                try {
                    $.post(wpacu_object.ajax_url, dataUpdateSetting, function (response) {
                        if (response == 'done') {
                            $('#wpacu-assets-groups-change-state-area').attr('data-wpacu-groups-current-state', newState);
                        }

                        $('#' + btnIdClicked).prop('disabled', false);
                    });
                } catch (e) {
                    $('#'+ btnIdClicked).prop('disabled', false); // Any problems with the AJAX call? Don't keep the button disabled
                }
            },
            wpacuAjaxUpdateKeepTheAssetRowState: function(newState, handle, handleFor, $currentElement) {
                var dataUpdateSetting = {
                    'action'                       : wpacu_object.plugin_prefix + '_update_asset_row_state',
                    'wpacu_update_asset_row_state' : 'yes',
                    'wpacu_asset_row_state'        : newState, // "expanded" or "contracted"
                    'wpacu_handle'                 : handle,
                    'wpacu_handle_for'             : handleFor,
                    'time_r'                       : new Date().getTime(), // avoid any caching
                    'wpacu_nonce'                  : wpacu_object.wpacu_update_asset_row_state_nonce
                };

                $currentElement.addClass('wpacu_hide');

                $.post(wpacu_object.ajax_url, dataUpdateSetting, function (response) {
                    $currentElement.removeClass('wpacu_hide');
                    console.log(response);
                });
            },

            wpacuTriggerAdjustTextAreaHeightAllTextareas: function() {
                // We use the "data-wpacu-adapt-height" attribute as a marker
                var wpacuTextAreas = [].slice.call(document.querySelectorAll('textarea[data-wpacu-adapt-height="1"]'));

                // Iterate through all the textareas on the page
                wpacuTextAreas.forEach(function(el) {
                    // we need box-sizing: border-box, if the textarea has padding
                    el.style.boxSizing = el.style.mozBoxSizing = 'border-box';

                    // we don't need any scrollbars, do we? :)
                    el.style.overflowY = 'hidden';

                    // the minimum height initiated through the "rows" attribute
                    var minHeight = el.scrollHeight;

                    el.addEventListener('input', function() {
                        $.fn.wpAssetCleanUp().wpacuAdjustTextareaHeight(el, minHeight);
                    });

                    // we have to readjust when window size changes (e.g. orientation change)
                    window.addEventListener('resize', function() {
                        $.fn.wpAssetCleanUp().wpacuAdjustTextareaHeight(el, minHeight);
                    });

                    // we adjust height to the initial content
                    $.fn.wpAssetCleanUp().wpacuAdjustTextareaHeight(el, minHeight);
                });
            },
            wpacuAdjustTextareaHeight: function(el, minHeight) {
                /* Source: http://bdadam.com/blog/automatically-adapting-the-height-textarea.html */
                // compute the height difference which is caused by border and outline
                var outerHeight = parseInt(window.getComputedStyle(el).height, 10);
                var diff = outerHeight - el.clientHeight;

                // set the height to 0 in case of it has to be shrunk
                el.style.height = 0;

                // set the correct height
                // el.scrollHeight is the full height of the content, not just the visible part
                el.style.height = Math.max(minHeight, el.scrollHeight + diff) + 'px';
            }
        }
    }
})(jQuery);

jQuery(document).ready(function($) {
    /*
    * [START] "Settings" (menu)
     */
    $.fn.wpAssetCleanUpSettingsArea = function() {
        return {
            actions: function () {
                /*
                * Settings: A link is clicked that should trigger a vertical menu link from the plugin
                 */
                $(document).on('click', 'a[data-wpacu-vertical-link-target]', function (e) {
                    //console.log('clicked...');
                    e.preventDefault();
                    $.fn.wpAssetCleanUpSettingsArea().tabOpenSettingsArea(e, $(this).attr('data-wpacu-vertical-link-target'));
                });

                /*
                 * A vertical tab is clicked
                 */
                $(document).on('click', 'a[data-wpacu-settings-tab-key]', function (e) {
                    //console.log('clicked...');
                    e.preventDefault();
                    $.fn.wpAssetCleanUpSettingsArea().tabOpenSettingsArea(e, $(this).attr('data-wpacu-settings-tab-key'));
                });

                $(document).on('click', 'input[type="checkbox"]#wpacu_disable_rss_feed', function () {
                    if ($(this).is(':checked')) {
                        $('#wpacu_remove_main_feed_link, #wpacu_remove_comment_feed_link').prop('checked', true);
                    } else {
                        $('#wpacu_remove_main_feed_link, #wpacu_remove_comment_feed_link').prop('checked', false);
                    }
                });

                /*
                * Settings: Sub-tab within tab clicked
                */
                $(document).on('click', 'input[name="wpacu_sub_tab_area"]', function () {
                    if ($(this).prop('checked')) {
                        $('#wpacu-selected-sub-tab-area').val($(this).val());
                    }
                });

                /* [Start] Minify/Combine CSS/JS status circles */
                $(document).on('click', '#wpacu_minify_css_enable, #wpacu_combine_loaded_css_enable, #wpacu_minify_js_enable, #wpacu_combine_loaded_js_enable, #wpacu_cdn_rewrite_enable, #wpacu_enable_test_mode', function () {
                    if ($(this).prop('checked')) {
                        $('[data-linked-to="' + $(this).attr('id') + '"]').find('.wpacu-circle-status').addClass('wpacu-on').removeClass('wpacu-off');
                    } else {
                        $('[data-linked-to="' + $(this).attr('id') + '"]').find('.wpacu-circle-status').addClass('wpacu-off').removeClass('wpacu-on');
                    }
                });
                /* [End] Minify/Combine CSS/JS status circles */

                /* [Start] Inline Stylesheet (.css) Files Smaller Than (x) KB */
                $(document).on('click', '#wpacu_inline_css_files_below_size_checkbox', function () {
                    // The checkbox is not 'checked' and it was clicked
                    if ($(this).is(':checked')) {
                        $('#wpacu_inline_css_files_enable').prop('checked', true).trigger('tick');
                    } else {
                        if ($('#wpacu_inline_css_files_list').val() === '') {
                            $('#wpacu_inline_css_files_enable').prop('checked', false).trigger('tick');
                        }
                    }
                });
                /* [End] Inline Stylesheet (.css) Files Smaller Than (x) KB */

                /* [Start] Inline JavaScript (.js) Files Smaller Than (x) KB */
                $(document).on('click', '#wpacu_inline_js_files_below_size_checkbox', function () {
                    // The checkbox is not 'checked' and it was clicked
                    if ($(this).is(':checked')) {
                        if (!confirm(wpacu_object.inline_auto_js_files_confirm_msg)) {
                            return false;
                        }

                        $('#wpacu_inline_js_files_enable').prop('checked', true).trigger('tick');
                    } else {
                        if ($('#wpacu_inline_js_files_list').val() === '') {
                            $('#wpacu_inline_js_files_enable').prop('checked', false).trigger('tick');
                        }
                    }
                });
                /* [End] Inline JavaScript (.js) Files Smaller Than (x) KB */

                // "Manage in the Dashboard?" Clicked
                $(document).on('click', '#wpacu_dashboard', function() {
                    if ($(this).prop('checked')) {
                        $('#wpacu-settings-assets-retrieval-mode').show();
                        //$('#wpacu_hide_meta_boxes_for_post_types_chosen .chosen-choices, #wpacu-hide-meta-boxes-for-post-types-info').css({'opacity':1});
                    } else {
                        $('#wpacu-settings-assets-retrieval-mode').hide();
                        //$('#wpacu_hide_meta_boxes_for_post_types_chosen .chosen-choices, #wpacu-hide-meta-boxes-for-post-types-info').css({'opacity':0.4});
                    }
                });

                // "Manage in the Dashboard?" radio selection
                $(document).on('change', '.wpacu-dom-get-type-selection', function() {
                    if ($(this).is(':checked')) {
                        $('.wpacu-dom-get-type-info').hide();
                        $('#'+ $(this).attr('data-target')).fadeIn('fast');
                    }
                });

                // "Manage in the Front-end?" Clicked
                $(document).on('click', '#wpacu_frontend', function() {
                    if ($(this).prop('checked')) {
                        $('#wpacu-settings-frontend-exceptions').show();
                    } else {
                        $('#wpacu-settings-frontend-exceptions').hide();
                    }
                });

                // Google Fonts: Load Optimizer (render-blocking or asynchronous)
                $(document).on('change', '.google_fonts_combine_type', function() {
                    $('.wpacu_google_fonts_combine_type_area').hide();

                    if ($(this).val() === 'async') {
                        $('#wpacu_google_fonts_combine_type_async_info_area').fadeIn();
                    } else if ($(this).val() === 'async_preload') {
                        $('#wpacu_google_fonts_combine_type_async_preload_info_area').fadeIn();
                    } else {
                        $('#wpacu_google_fonts_combine_type_rb_info_area').fadeIn();
                    }
                });

                if ( $('#wpacu-allow-manage-assets-to-select-list-area').length > 0
                    && ( ! $('#wpacu-allow-manage-assets-to-select-list-area').hasClass('wpacu_hide') ) ) {
                    setTimeout(function() { jQuery('#wpacu-allow-manage-assets-to-select-list').chosen(); }, 200);
                }

                $('#wpacu-allow-manage-assets-to-select').on('click change', function() {
                    if ($(this).val() === 'chosen') {
                        $('#wpacu-allow-manage-assets-to-select-list-area').removeClass('wpacu_hide');
                        setTimeout(function() { jQuery('#wpacu-allow-manage-assets-to-select-list').chosen(); }, 200);
                    } else {
                        $('#wpacu-allow-manage-assets-to-select-list-area').addClass('wpacu_hide');
                    }
                });

                $('#wpacu_assets_list_layout').on('click change', function() {
                    if ($(this).val() === 'by-location') {
                        $('#wpacu-assets-list-by-location-selected').fadeIn('fast');
                    } else {
                        $('#wpacu-assets-list-by-location-selected').fadeOut('fast');
                    }
                });

                $('#wpacu_disable_jquery_migrate').on('click', function() {
                    // It was checked and the user unchecked it
                    if (! $(this).is(':checked')) {
                        return true;
                    }

                    // It was unchecked and the user checked it, needs confirmation
                    // Otherwise, it would be reversed as not checked
                    if ($(this).is(':checked') && confirm(wpacu_object.jquery_migration_disable_confirm_msg)) {
                        return true;
                    } else {
                        // Not confirmed?
                        $(this).prop('checked', false);
                        return false;
                    }
                });

                $('#wpacu_disable_comment_reply').on('click', function() {
                    // It was checked and the user unchecked it
                    if (! $(this).is(':checked')) {
                        return true;
                    }

                    // It was unchecked and the user checked it, needs confirmation
                    // Otherwise, it would be reversed as not checked
                    if ($(this).is(':checked') && confirm(wpacu_object.comment_reply_disable_confirm_msg)) {
                        return true;
                    } else {
                        // Not confirmed?
                        $(this).prop('checked', false);
                        return false;
                    }
                });

                // "Settings" - When an option is enabled/disabled
                $('[data-target-opacity]').on('click change tick', function() {
                    if ($(this).prop('checked')) {
                        $('#'+ $(this).attr('data-target-opacity')).css({'opacity':1});
                    } else {
                        $('#'+ $(this).attr('data-target-opacity')).css({'opacity':0.4});
                    }
                });

                $('#wpacu-show-assets-meta-box-checkbox').on('click change', function() {
                    if ($(this).prop('checked')) {
                        $('#wpacu-show-assets-enabled-area').show();
                        $('#wpacu-show-assets-disabled-area').hide();
                    } else {
                        $('#wpacu-show-assets-enabled-area').hide();
                        $('#wpacu-show-assets-disabled-area').show();
                    }
                });

                // "Combine JS Files" - "Select a combination method:"
                $(document).on('change', '.wpacu-combine-loaded-js-level', function() {
                    if ($(this).is(':checked')) {
                        $('.wpacu_combine_loaded_js_level_area').removeClass('wpacu_active');
                        $('#'+ $(this).attr('data-target')).addClass('wpacu_active');
                    }
                });

                // Submit button (Dashboard) is clicked
                var $settingSubmitBtn = $('#wpacu-update-button-area input[type="submit"]');

                // Show the loading spinner
                $(document).on('submit', '#wpacu-settings-form, .wpacu-settings-form', function() {
                    $settingSubmitBtn.attr('disabled', true);
                    $('#wpacu-updating-settings').addClass('wpacu-show').removeClass('wpacu-hide');
                });

                // Once the form is submitted, disable the submit button to prevent any double submission
                // Settings & Homepage Buttons
                $(document).on('submit', 'form#wpacu-settings-form, form#wpacu_dash_assets_manager_form', function() {
                    $settingSubmitBtn.attr('disabled', true);
                    $('#wpacu-updating-settings').show();
                    return true;
                });
            },

            tabOpenSettingsArea: function(evt, settingName) {
                /*
                * Only relevant in the "Settings" area
                */
                evt.preventDefault();

                var i, wpacuVerticalTabContent, wpacuVerticalTabLinks;

                wpacuVerticalTabContent = document.getElementsByClassName("wpacu-settings-tab-content");

                for (i = 0; i < wpacuVerticalTabContent.length; i++) {
                    wpacuVerticalTabContent[i].style.display = "none";
                }

                wpacuVerticalTabLinks = document.getElementsByClassName("wpacu-settings-tab-link");

                for (i = 0; i < wpacuVerticalTabLinks.length; i++) {
                    wpacuVerticalTabLinks[i].className = wpacuVerticalTabLinks[i].className.replace(" active", "");
                }

                document.getElementById(settingName).style.display = "table-cell";

                $('a[href="#'+ settingName +'"]').addClass('active');
                $('#wpacu-selected-tab-area').val(settingName);

                },
        }
    }
    $.fn.wpAssetCleanUpSettingsArea().actions();
    /*
    * [END] "Settings" (menu)
     */

    /*
    * [START] "Tools" (menu)
     */
    $.fn.wpAssetCleanUpToolsArea = function() {
        return {
            actions: function () {
                /*
                * "Tools" -> "Reset"
                */
                var wpacuResetDdSelector = '#wpacu-reset-drop-down', $wpacuOptionSelected, wpacuMsgToShow;

                $(wpacuResetDdSelector).on('change keyup keydown mouseup mousedown click', function() {
                    if ($(this).val() === '') {
                        $('#wpacu-warning-read').removeClass('wpacu-visible');
                        $('#wpacu-reset-submit-btn').attr('disabled', 'disabled')
                            .removeClass('button-primary')
                            .addClass('button-secondary');
                    } else {
                        if ($(this).val() === 'reset_everything') {
                            $('#wpacu-license-data-remove-area, #wpacu-cache-assets-remove-area').addClass('wpacu-visible');
                        } else {
                            $('#wpacu-license-data-remove-area, #wpacu-cache-assets-remove-area').removeClass('wpacu-visible');
                        }

                        $('#wpacu-warning-read').addClass('wpacu-visible');
                        $('#wpacu-reset-submit-btn').removeAttr('disabled')
                            .removeClass('button-secondary')
                            .addClass('button-primary');
                    }

                    $('.wpacu-tools-area .wpacu-warning').hide();

                    $wpacuOptionSelected = $(this).find('option:selected');
                    $('#'+ $wpacuOptionSelected.attr('data-id')).show();
                });

                $('#wpacu-reset-submit-btn').on('click', function() {
                    if ($(wpacuResetDdSelector).val() === 'reset_settings') {
                        wpacuMsgToShow = wpacu_object.reset_settings_confirm_msg;
                    } else if ($(wpacuResetDdSelector).val() === 'reset_critical_css') {
                        wpacuMsgToShow = wpacu_object.reset_critical_css_confirm_msg;
                    } else if ($(wpacuResetDdSelector).val() === 'reset_everything_except_settings') {
                        wpacuMsgToShow = wpacu_object.reset_everything_except_settings_confirm_msg;
                    } else if ($(wpacuResetDdSelector).val() === 'reset_everything') {
                        wpacuMsgToShow = wpacu_object.reset_everything_confirm_msg;
                    }

                    if (! confirm(wpacuMsgToShow)) {
                        return false;
                    }

                    $('#wpacu-action-confirmed').val('yes');

                    setTimeout(function() {
                        if ($('#wpacu-action-confirmed').val() === 'yes') {
                            $('#wpacu-tools-form').trigger('submit');
                        }
                    }, 1000);
                });

                /*
                * "Tools" -> "Import"
                */
                $(document).on('submit', '#wpacu-import-form', function() {
                    if (! confirm(wpacu_object.import_confirm_msg)) {
                        return false;
                    }

                    $(this).find('button').addClass('wpacu-importing').prop('disabled', true);
                });
            }
        }
    }
    $.fn.wpAssetCleanUpToolsArea().actions();
    /*
    * [END] "Tools" (menu)
     */

    /*
     * [START] Front-end CSS/JS Manager
     */
    $.fn.wpAssetCleanUpFrontendCssJsManagerArea = function() {
        return {
            actions: function () {
                // "Update" button is clicked within front-end view
                var $updateBtnFrontEnd = $('#wpacu-update-front-settings-area .wpacu_update_btn');

                // Show the loading spinner
                $(document).on('submit', '#wpacu-frontend-form', function() {
                    $updateBtnFrontEnd.attr('disabled', true).addClass('wpacu_submitting');
                    $('#wpacu-updating-front-settings').show();
                    return true;
                });

                // Asset Front-end Edit (if setting is enabled)
                if ($('#wpacu_wrap_assets').length > 0) {
                    setTimeout(function () {
                        $.fn.wpAssetCleanUp().cssJsManagerActions();
                    }, 200);
                }

                // The code below is for the pages loaded in the front-end view
                // Fetch hardcoded assets
                if ($('#wpacu-assets-collapsible-wrap-hardcoded-list').length > 0) {
                    var dataFetchHardcodedList = {};
                    dataFetchHardcodedList[wpacu_object.plugin_prefix + '_load']   = 1;
                    dataFetchHardcodedList[wpacu_object.plugin_prefix + '_time_r'] = new Date().getTime();
                    dataFetchHardcodedList['wpacu_just_hardcoded']                 = 1;

                    $.ajax({
                        method: 'GET',
                        url: wpacu_object.page_url,
                        data: dataFetchHardcodedList,
                        cache: false,
                        complete: function (xhr, textStatus) {
                            //console.log(xhr);
                            if (xhr.statusText === 'error') {
                                $.fn.wpAssetCleanUp().wpacuParseResultsForHarcodedAssets(xhr.responseText);
                                }
                        }
                    }).done(function (contents) {
                        $.fn.wpAssetCleanUp().wpacuParseResultsForHarcodedAssets(contents);
                    });
                }
            }
        }
    }
    $.fn.wpAssetCleanUpFrontendCssJsManagerArea().actions();
    /*
     * [END] Front-end CSS/JS Manager
     */

    /*
    * [START] Dashboard CSS/JS Manager
    */
    $.fn.wpAssetCleanUpDashboardCssJsManagerArea = function() {
        return {
            actions: function () {
                // Option #1: Fetch the assets automatically and show the list (Default) is chosen
                // Or "Homepage" from "CSS & JavaScript Load Manager" is loaded
                if (wpacu_object.list_show_status === 'default' || wpacu_object.list_show_status === '' || wpacu_object.override_assets_list_load) {
                    $.fn.wpAssetCleanUp().wpacuAjaxGetAssetsArea(false);
                }

                // Option #2: Fetch the assets on button click
                // This takes effect only when edit post/page is used - e.g. /wp-admin/post.php?post=[post_id_here]&action=edit
                if (wpacu_object.list_show_status === 'fetch_on_click') {
                    $(document).on('click', '#wpacu_ajax_fetch_on_click_btn', function(e) {
                        e.preventDefault();
                        $(this).hide(); // Hide the button
                        $('#wpacu_fetching_assets_list_wrap').show(); // Show the loading information
                        $.fn.wpAssetCleanUp().wpacuAjaxGetAssetsArea(true); // Fetch the assets list
                    });
                }

                // Better compatibility with WordPress 5.0 as edit post/page is not refreshed after update
                // Asset CleanUp meta box's content is refreshed to show the latest changes as if the page was refreshed
                // This takes effect only when edit post/page is used and Gutenberg editor is used - e.g. /wp-admin/post.php?post=[post_id_here]&action=edit
                $(document).on('click', '.wp-admin.post-php .edit-post-header__settings button.is-primary', function () {
                    $.fn.wpAssetCleanUp().limitSubmittedFields();

                    var $thisUpdateBtn = $(this);

                    // Wait until triggering it around half a second after the "Update" button is clicked
                    setTimeout(function() {
                        var wpacuIntervalUpdateAction = function () {
                            // If it's in the updating status, don't do anything
                            if ($thisUpdateBtn.attr('aria-disabled') === 'true' || $('#editor').hasClass('is-validating')) {
                                return;
                            }

                            // If the button "Fetch CSS & JavaScript Management List" is there, stop here as the list shouldn't be loaded
                            // since the admin didn't use the button in the first place
                            if ($('#wpacu_ajax_fetch_on_click_btn').length > 0) {
                                return;
                            }

                            // Updating status is over. Reload the CSS/JS manager which would show the new list
                            // (e.g. a site-wide rule could be applied, and it needs to show the removing "radio input" option)
                            if ($('.edit-post-header__settings .is-saving').length === 0) {
                                var wpacuMetaBoxContentTarget = '#wpacu_meta_box_content';

                                if ($(wpacuMetaBoxContentTarget).length > 0) {
                                    $('#wpacu-assets-reloading').remove();
                                    var wpacuAppendToPostWhileUpdating = '<span id="wpacu-assets-reloading" class="editor-post-saved-state is-wpacu-reloading">' + wpacu_object.reload_icon + wpacu_object.reload_msg + '</span>';
                                    $('.wp-admin.post-php .edit-post-header__settings').prepend(wpacuAppendToPostWhileUpdating);

                                    $('.wpacu_asset_row, .wpacu-page-options .wpacu-assets-collapsible-content').addClass('wpacu-loading'); // show loading spinner once "Update" is clicked

                                    $.fn.wpAssetCleanUp().wpacuAjaxGetAssetsArea(true);
                                    $.fn.wpAssetCleanUpClearCache().wpacuAjaxClearCache();

                                    // Finally, after the list is fetched and the caching is cleared,
                                    // do not keep checking any "saving" status as all the needed actions have been taken
                                    clearInterval(wpacuUpdateIntervalId);
                                }
                            }
                        };

                        var wpacuUpdateIntervalId = setInterval(wpacuIntervalUpdateAction, 900);
                    }, 500);
                });
            }
        }
    }
    $.fn.wpAssetCleanUpDashboardCssJsManagerArea().actions();
    /*
    * [END] Dashboard CSS/JS Manager
    */

    /*
    * [START] Common CSS/JS Manager (Dashboard & Front-end)
    */
    $.fn.wpAssetCleanUpCommonCssJsManagerArea = function() {
        return {
            actions: function () {
                // Mark specific inputs as disabled if they are not needed to further reduce the total PHP inputs
                // if "max_input_vars" from php.ini is not set high enough
                $(document).on('submit', 'form#wpacu-frontend-form, form#wpacu_dash_assets_manager_form, body.wp-admin form#post, body.wp-admin #edittag', function() {
                    return $.fn.wpAssetCleanUp().limitSubmittedFields();
                });

                // Source (updated)
                $(document).on('click', '.wpacu-filter-handle', function(event) {
                    alert($(this).attr('data-wpacu-filter-handle-message'));
                    event.preventDefault();
                });

                // "Contract All Groups"
                $(document).on('click', '#wpacu-assets-contract-all', function() {
                    $(this).prop('disabled', true); // avoid multiple clicks and AJAX calls
                    $.fn.wpAssetCleanUp().wpacuAjaxUpdateKeepTheGroupsState('contracted', $(this).attr('id'));
                });

                // "Expand All Groups"
                $(document).on('click', '#wpacu-assets-expand-all', function() {
                    $(this).prop('disabled', true); // avoid multiple clicks and AJAX calls
                    $.fn.wpAssetCleanUp().wpacuAjaxUpdateKeepTheGroupsState('expanded', $(this).attr('id'));
                });
            }
        }
    }
    $.fn.wpAssetCleanUpCommonCssJsManagerArea().actions();
    /*
    * [END] Common CSS/JS Manager (Dashboard & Front-end)
    */

    $.fn.wpAssetCleanUpClearCache = function() {
        return {
            init: function() {
                // The assets of a page just had rules applied (e.g. assets were unloaded)
                if (wpacu_object.clear_cache_on_page_load !== '') {
                    $.fn.wpAssetCleanUpClearCache().wpacuAjaxClearCache();
                }

                if (wpacu_object.clear_other_caches !== '') {
                    setTimeout(function () {
                        $.fn.wpAssetCleanUpClearCache().wpacuClearAutoptimizeCache(); // Autoptimize (if active)
                    }, 150);
                }
            },

            afterSubmit: function () {
                try {
                    var httpRefererFieldTargetName = 'input[type="hidden"][name="_wp_http_referer"]',
                        wpacuHttpRefererFieldVal;

                    if ($(httpRefererFieldTargetName).length > 0) {
                        wpacuHttpRefererFieldVal = $(httpRefererFieldTargetName).val();

                        // Edit Taxonomy page (after submit)
                        if (wpacuHttpRefererFieldVal.includes('term.php?taxonomy=') && wpacuHttpRefererFieldVal.includes('message=')) {
                            $.fn.wpAssetCleanUpClearCache().wpacuAjaxClearCache();
                        }

                        // Edit (Post/Page/Custom Post type) page (after submit)
                        if (wpacuHttpRefererFieldVal.includes('post.php?post=') && wpacuHttpRefererFieldVal.includes('message=')) {
                            $.fn.wpAssetCleanUpClearCache().wpacuAjaxClearCache();
                        }
                    }
                } catch (e) {
                    console.log(e);
                }
            },

            wpacuAjaxClearCache: function() {
                /**
                 * Called after a post/page is saved (WordPress AJAX call)
                 */
                if (typeof wpacu_object.wpacu_ajax_preload_url_nonce === 'undefined') {
                    return;
                }

                // Is the post status a "draft" one? Do not do any cache clearing and preloading as it's useless
                var $wpacuHiddenPostStatusEl = '#hidden_post_status';
                if ($($wpacuHiddenPostStatusEl).length > 0 && $($wpacuHiddenPostStatusEl).val() === 'draft') {
                    return;
                }

                $.post(wpacu_object.ajax_url, {
                    'action'      : wpacu_object.plugin_prefix + '_clear_cache',
                    'time_r'      : new Date().getTime(),
                    'wpacu_nonce' : wpacu_object.wpacu_ajax_clear_cache_nonce
                }, function (response) {
                    setTimeout(function() {
                        $.fn.wpAssetCleanUpClearCache().wpacuClearAutoptimizeCache(); // Autoptimize (if active)

                        if (wpacu_object.is_frontend_view) {
                            // Preload (for the guest)
                            // The preload for the admin is not needed as the user is managing the CSS/JS in the front-end view and the page has been already visited
                            $.post(wpacu_object.ajax_url, {
                                'action':       wpacu_object.plugin_prefix + '_preload',
                                'page_url':     wpacu_object.page_url,
                                'wpacu_nonce':  wpacu_object.wpacu_ajax_preload_url_nonce,
                                'time_r':       new Date().getTime()
                            });
                        } else {
                            // Preload (for the admin)
                            $.get(wpacu_object.page_url, {
                                'wpacu_preload': 1,
                                'wpacu_no_frontend_show': 1,
                                'time_r': new Date().getTime()
                            }, function () {
                                // Then, preload (for the guest)
                                $.post(wpacu_object.ajax_url, {
                                    'action':       wpacu_object.plugin_prefix + '_preload',
                                    'page_url':     wpacu_object.page_url,
                                    'wpacu_nonce':  wpacu_object.wpacu_ajax_preload_url_nonce,
                                    'time_r':       new Date().getTime()
                                });
                            });
                        }
                    }, 150);
                });
            },

            wpacuClearAutoptimizeCache: function() {
                if (wpacu_object.clear_autoptimize_cache == 'false') {
                    console.log(wpacu_object.plugin_title + ': Autoptimize cache clearing is deactivated via "WPACU_DO_NOT_ALSO_CLEAR_AUTOPTIMIZE_CACHE" constant.');
                    return;
                }

                var wpacuAutoptimizeClickEl = '#wp-admin-bar-autoptimize-default li';

                // Autoptimize elements & variables: make sure they are all initialized
                if ($(wpacuAutoptimizeClickEl).length > 0
                    && typeof autoptimize_ajax_object.ajaxurl !== 'undefined'
                    && typeof autoptimize_ajax_object.nonce !== 'undefined') {
                    $.ajax({
                        type     : 'GET',
                        url      : autoptimize_ajax_object.ajaxurl,
                        data     : {'action' : 'autoptimize_delete_cache', 'nonce' : autoptimize_ajax_object.nonce},
                        dataType : 'json',
                        cache    : false,
                        timeout  : 9000,
                        success  : function( cleared ) {},
                        error    : function( jqXHR, textStatus ) {}
                    });
                }
            },
        }
    }
    $.fn.wpAssetCleanUpClearCache().init();

    $.fn.wpAssetCleanUp().wpacuTriggerAdjustTextAreaHeightAllTextareas();

    /*
    * [START] Bulk Changes
    */
    $.fn.wpAssetCleanUpBulkChangesArea = function() {
        return {
            actions: function() {
                // Items are marked for removal from the unload list
                // from either "Everywhere" or "Post Type"
                $(document).on('click', '.wpacu_bulk_rule_checkbox, .wpacu_remove_preload', function() {
                    var $wpacuBulkChangeRow = $(this).parents('.wpacu_bulk_change_row');

                    if ($(this).prop('checked')) {
                        $wpacuBulkChangeRow.addClass('wpacu_selected');
                    } else {
                        $wpacuBulkChangeRow.removeClass('wpacu_selected');
                    }
                });

                $(document).on('change', '#wpacu_post_type_select', function() {
                    $('#wpacu_post_type_form').trigger('submit');
                });
            }
        }
    }
    $.fn.wpAssetCleanUpBulkChangesArea().actions();
    /*
    * [END] Bulk Changes
    */
});

(function($){
    $(window).on('load', function() {
        $.fn.wpAssetCleanUp().wpacuCheckSourcesFor404Errors();
    });
})(jQuery);
//
// [END] Core file
//
