;/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};
    window.wp.dev4press.ctrl = window.wp.dev4press.ctrl || {};

    window.wp.dev4press.ctrl.tabs = {
        run: function() {
            $(document).on(
                "click",
                ".d4p-ctrl-tabs button[role='tab']",
                function(e) {
                    var tabs = $(this).parent(),
                        content = tabs.closest(".d4p-ctrl-tabs"),
                        id = $(this).attr("id"),
                        tab = $(this).attr("aria-controls");

                    $("button[role='tab']", tabs).attr("aria-selected", "false").removeClass("d4p-ctrl-tab-is-active");
                    $("button[role='tab']#" + id, tabs).attr("aria-selected", "true").addClass("d4p-ctrl-tab-is-active");

                    $("div[role='tabpanel']", content).attr("hidden", "hidden").removeClass("d4p-ctrl-tabs-content-active");
                    $("div[role='tabpanel']#" + tab, content).removeAttr("hidden").addClass("d4p-ctrl-tabs-content-active");
                }
            );

            $(document).on(
                "keydown",
                ".d4p-ctrl-tabs button[role='tab']",
                function(e) {
                    if (e.which === 13) {
                        $(this).click();
                    } else if (e.which === 39) {
                        $(this).next().focus().click();
                    } else if (e.which === 37) {
                        $(this).prev().focus().click();
                    }
                }
            );
        }
    };

    $(document).ready(
        function() {
            window.wp.dev4press.ctrl.tabs.run();
        }
    );
})(jQuery, window, document);
