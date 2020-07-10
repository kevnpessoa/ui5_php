sap.ui.define([
    '../BaseController'
], function (BaseController) {
    "use strict";

    return BaseController.extend("softpro.portal.controller.pessoa.Pessoa", {

        modelName: "user",
        formName: "pessoaForm",

        onInit: function () {
            this.getRecords();
        }
    });
});