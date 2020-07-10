sap.ui.define([
	'./FormController'
], function (FormController, Usuario) {
	"use strict";
	
	return FormController.extend("softpro.portal.controller.UsuarioForm", {

		modelName: "user",
		formName: "usuarioForm",
		textEdition: "updateUsuario",
		addEdition: "createUsuario"

	});
});