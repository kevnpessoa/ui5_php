sap.ui.define([
	'../FormController'
], function (FormController) {
	"use strict";
	
	return FormController.extend("softpro.portal.controller.pessoa.PessoaForm", {

		modelName: "user",
		formName: "pessoaForm",
		textEdition: "updatePessoa",
		addEdition: "createPessoa"

	});
});