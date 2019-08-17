/*
\ * 	公共弹出层提示，基于dialog.js
 *     - lanqy 2015-12-30
*/
Utils = {

    _alert: function(type, msg, config, callback) {

        var config, dialogSelector = "#alert_panel",
            tpl = '<div id="$id"></div>',
            dialog = null,
            dialogClass = "alert-box";

        if (type === "error") {
            dialogSelector = "#error_panel";
        } else if (type === "confirm") {
            dialogSelector = "#confirm_panel";
        }

        if (typeof callback === 'function') {
            callback = callback;
        } else {
            callback = false;
        }

        if (type === 'alert') {
            var _obj = {
                title: '提示信息',
                dragable: true,
                onClose: callback,
                showFooter: false
            }
            config = $.extend(true, _obj, config);
        } else if (type === 'confirm') {
            var _obj = {
                title: '提示信息',
                dragable: true,
                cannelText: '取消',
                confirmText: '确认',
                showFooter: true,
                onConfirm: callback
            }
            config = $.extend(true, _obj, config);
        }

        dialog = $(dialogSelector), dialog.length < 1 && ($(tpl.replace(/\$id/, dialogSelector.substring(1))).appendTo("body"), dialog = $(dialogSelector)), dialog.data("box") || dialog.dialog(config),
            dialog.find(".body-content").html(msg);
        dialog.open();
    },

    error: function(msg, config, callback) {
        Utils._alert("error", msg, config, callback)
    },

    alert: function(msg, config, callback) {
        Utils._alert("alert", msg, config, callback)
    },

    confirm: function(msg, config, callback) {
        Utils._alert("confirm", msg, config, callback)
    }
}
