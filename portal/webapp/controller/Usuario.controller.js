sap.ui.define([
    './BaseController'
], function (BaseController) {
    "use strict";

    return BaseController.extend("softpro.portal.controller.Usuario", {

        modelName: "user",
        formName: "usuarioForm",

        onInit: function () {
            this.getRecords();
        }
    });
});