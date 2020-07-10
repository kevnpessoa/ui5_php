sap.ui.define([
	"sap/ui/core/mvc/Controller",
	"sap/ui/core/UIComponent",
	"sap/ui/model/json/JSONModel",
	"sap/ui/core/routing/History",
	"sap/ui/model/Filter",
	"sap/ui/model/FilterOperator"
], function (Controller, UIComponent, JSONModel, History, Filter, FilterOperator) {
	"use strict";

	return Controller.extend("softpro.portal.controller.BaseController", {

		serviceUrl: "http://localhost/visitas/srv/",
		modelName: "",
		formName: "",

		getRouter: function () {
			return UIComponent.getRouterFor(this);
		},

		getModel: function (sName) {
			return this.getView().getModel(sName);
		},

		setModel: function (oModel, sName) {
			return this.getView().setModel(oModel);
		},

		getBundleTextByModel: function (sI18nKey, oResourceModel, aPlaceholderValues) {
			return oResourceModel.getResourceBundle().getText(sI18nKey);
		},

		getText: function(sI18nKey) {
			return this.getModel("i18n").getResourceBundle().getText(sI18nKey);
		},

		getRecords: function() {
			var me = this;

			this.callApi({type: "GET", serviceApi: me.modelName}, function (oData) {
				me.setModel(new JSONModel(oData, me.modelName), me.modelName);
			});
		},

		/**
		 *
		 * @param objAjax - {type, serviceApi, params}
		 * @param cbFunc - function callback
		 */
		callApi: function (objAjax, cbFunc) {
			var urlCompl = objAjax.serviceApi;
			if (objAjax.params) {
				urlCompl += objAjax.params;
			}

			$.ajax({
				type: objAjax.type, //"GET",
				dataType: "json",
				context : this,
				url: this.serviceUrl + urlCompl, //objAjax.serviceApi + objAjax.params,
				success: function (oData, sStatus, jqXHR) {
					if (cbFunc) {
						cbFunc(oData);
					}
				}
			});
		},

		/**
		 *
		 * @param objAjax - {type, serviceApi, params}
		 * @param cbFunc - function callback
		 */
		callApiPost: function (objAjax, cbFunc) {
			$.ajax({
				type: objAjax.type, //"POST",
				data: $.param(objAjax.params),
				context : this,
				url: this.serviceUrl + objAjax.serviceApi,
				contentType: 'application/x-www-form-urlencoded',
				success: function (oData, sStatus, jqXHR) {
					if (cbFunc) {
						cbFunc(true, oData, null);
					}
				},
				error: function(e) {
					if (cbFunc) {
						cbFunc(false, null, e);
					}
				}
			});
		},

		onPressAdd: function (oEvent) {
			this.getRouter().navTo(this.formName, {
				id: 0
			});
		},

		onPressItem: function (oEvent) {
			this.getRouter().navTo(this.formName, {
				id: oEvent.getSource().getBindingContext().getProperty("id")
				//row: JSON.stringify(oEvent.getSource().getBindingContext().getProperty())
			});
		},

		onSearch: function (oEvent) {
			var aFilter = [];
			var sQuery = oEvent.getParameter("query");
			if (sQuery) {
				aFilter.push(new Filter("nome", FilterOperator.Contains, sQuery));
			}

			// filter binding
			var oTable = this.byId("table");
			var oBinding = oTable.getBinding("items");
			oBinding.filter(aFilter);
		},

		myNavBack: function (sRoute, mData) {
			var oHistory = History.getInstance();
			var sPreviousHash = oHistory.getPreviousHash();

			if (sPreviousHash !== undefined) {
				history.go(-1);
			} else {
				var bReplace = true;
				this.getRouter().navTo(sRoute, mData, bReplace);
			}
		}
	});

});