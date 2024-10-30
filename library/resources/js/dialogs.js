;/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global d4plib_admin_dialogs*/

(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};

    window.wp.dev4press.dialogs = {
        storage: {
            url: ''
        },
        classes: function(extra, hide_close) {
            var cls = "wp-dialog d4p-dialog d4p-dialog-modal";

            if (extra !== "") {
                cls += " " + extra;
            }

            if (typeof hide_close !== "undefined") {
                if (hide_close) {
                    cls += " d4p-dialog-hidex";
                }
            }

            return cls;
        },
        default_button: function(button, has_focus, button_text) {
            var id = "d4p-dialog-button-id-" + button,
                cls = "d4p-dialog-button-" + button + (has_focus ? " button-has-focus" : ""),
                text = typeof button_text !== "undefined" ? button_text : d4plib_admin_dialogs.buttons[button];

            return {
                id: id,
                class: cls,
                text: text,
                data: {
                    icon: button
                }
            };
        },
        default_dialog: function() {
            return {
                width: 480,
                height: "auto",
                minHeight: 24,
                autoOpen: false,
                resizable: false,
                modal: true,
                closeOnEscape: false,
                zIndex: 300000,
                open: function() {
                    $(".button-has-focus").focus();
                }
            };
        },
        icons: function(id, icon_html) {
            $(id).next().find(".ui-dialog-buttonset button").each(
                function() {
                    if ($(this).find("span.ui-button-text").length === 0) {
                        $(this).html("<span class='ui-button-text'>" + $(this).html() + "</span>");
                    }

                    if (typeof icon_html === "undefined") {
                        var icon = $(this).data("icon");

                        if (icon !== "") {
                            $(this).find("span.ui-button-text").prepend(d4plib_admin_dialogs.icons[icon]);
                        }
                    } else {
                        $(this).find("span.ui-button-text").prepend(icon_html);
                    }
                }
            );
        }
    };
})(jQuery, window, document);
