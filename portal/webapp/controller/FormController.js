sap.ui.define([
    './BaseController',
    "sap/ui/model/json/JSONModel",
    "sap/m/MessageToast",
    "sap/m/MessageBox"
], function (BaseController, JSONModel, MessageToast, MessageBox) {
    "use strict";

    return BaseController.extend("softpro.portal.controller.FormController", {

        formName: "",
        textEdition: "",
        addEdition: "",

        onInit: function () {
            this.getRouter().getRoute(this.formName).attachPatternMatched(this._onPostMatched, this);
        },

        getOData: function() {
            return this.getView().getModel().oData;
        },

        getId: function() {
            return this.getOData().id;
        },

        onNavBack: function () {
            this.myNavBack(this.formName.replace("Form", ""));
        },

        _onPostMatched: function (oEvent) {
            var me = this;
            var id = oEvent.getParameter("arguments").id;
            //this.setRecordForm(JSON.parse(oEvent.getParameter("arguments").row));

            if (id && id != "0") { //Edição
                var objAjax = {
                    type: "GET",
                    serviceApi: me.modelName,
                    params: "/" + id
                };

                this.callApi(objAjax, function (oData) {
                    me.setModel(new JSONModel(oData));
                });

                this.byId("page").setTitle(this.getTextEdition());
                this.byId("btnDelete").setVisible(true);
            }
            else { //Criação
                var oModel = this.getView().getModel();

                if (oModel) {
                    oModel.setData({});
                    oModel.updateBindings(true);
                }

                this.byId("page").setTitle(this.getTextAdd());
                this.byId("btnDelete").setVisible(false);
            }
        },

        getTextEdition: function () {
            return this.getText(this.textEdition);
        },

        getTextAdd: function () {
            return this.getText(this.addEdition);
        },

        onPressSave: function (oEvent) {
            this.saveRecord(oEvent);
        },

        saveRecord: function(oEvent) {
            var me = this;
            var params = this.getOData();
            var method = this.getId() ? "PUT" : "POST";
            var objAjax = {type: method, serviceApi: me.modelName, params: params};

            this.callApiPost(objAjax, function (success, oData, msg) {
                if (success) {
                    MessageToast.show(me.getText("recordSavedText"));

                    me.onNavBack();
                } else {
                    MessageBox.error(me.getText("errorSaveText") + " " + msg);
                }
            });
        },

        onPressDelete: function (oEvent) {
            this.deleteRecord(oEvent);
        },

        deleteRecord: function(oEvent) {
            var me = this;
            var objAjax = {type: "DELETE", serviceApi: me.modelName, params: {id: this.getId()}};

            var func = function() {
                me.callApiPost(objAjax, function (success, oData, msg) {
                    if (success) {
                        MessageToast.show(me.getText("recordRemovedText"));

                        me.onNavBack();
                    } else {
                        MessageBox.error(me.getText("errorRemoveText") + " " + msg);
                    }
                });
            };

            MessageBox.show(
                me.getText("questionRemoveText"), {
                    icon: MessageBox.Icon.QUESTION,
                    title: me.getText("warningText"),
                    actions: [MessageBox.Action.YES, MessageBox.Action.NO],
                    onClose: function(sAnswer) {
                        if (sAnswer === MessageBox.Action.YES) {
                            func();
                        }
                    }
                }
            );
        }
    });
});