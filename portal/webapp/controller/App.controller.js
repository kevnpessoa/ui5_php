sap.ui.define([
	'./BaseController',
	'sap/m/ActionSheet',
	'sap/m/Button',
	'sap/ui/core/syncStyleClass'
], function (BaseController, ActionSheet, Button, syncStyleClass) {
	"use strict";

	return BaseController.extend("softpro.portal.controller.App", {

		onItemSelect: function(oEvent) {
			this.getRouter().navTo(oEvent.getParameter("item").getKey());
		},

		getBundleText: function(sI18nKey, aPlaceholderValues) {
			return this.getBundleTextByModel(sI18nKey, this.getModel("i18n"), aPlaceholderValues);
		},

		onPressTest: function() {
			this.getRouter().navTo("usuario");
		},

		onSideNavButtonPress: function() {
			var oToolPage = this.byId("app");
			var bSideExpanded = oToolPage.getSideExpanded();
			this._setToggleButtonTooltip(bSideExpanded);
			oToolPage.setSideExpanded(!oToolPage.getSideExpanded());
		},

		_setToggleButtonTooltip : function(bSideExpanded) {
			this.byId('sideNavigationToggleButton')
				.setTooltip(this.getText(bSideExpanded ? "expandMenuButtonText" : "collpaseMenuButtonText"));
		},

		onUserNamePress: function(oEvent) {
			var me = this;

			var oMessagePopover = this.byId("errorMessagePopover");
			if (oMessagePopover && oMessagePopover.isOpen()) {
				oMessagePopover.destroy();
			}

			var oActionSheet = new ActionSheet(this.getView().createId("userMessageActionSheet"), {
				title: me.getText("userHeaderTitle"),
				showCancelButton: false,
				buttons: [
					new Button({
						text: me.getText("userAccountLogout"),
						type: 'Transparent',
						press: function (oEvent) {
							me.logout();
						}
					})
				],
				afterClose: function () {
					oActionSheet.destroy();
				}
			});
			this.getView().addDependent(oActionSheet);
			syncStyleClass(this.getView().getController().getOwnerComponent().getContentDensityClass(), this.getView(), oActionSheet);
			oActionSheet.openBy(oEvent.getSource());
		},

		logout: function () {
			alert("logout");
		}
	});
});