// para uso entre webserver con httaccess vs php built in development server
//uri_prefix="";  // httaccess
uri_prefix = "index.php/"; // built in

_AJAX = {
	/**
	 * /
	 * GENERAL
	 */
	_pre: "",
	_waiter: false,
	server: (window.location.protocol + "//" + window.location.host + "/"),
	_here: (window.location.protocol + "//" + window.location.host + "/"),
	_remote_mode: (typeof window.parent.ripple === "undefined"),
	_ready: false,
	_user_firebase: null,
	_uid: null,
	_id_app: null,
	_id_channel: null,
	_channels: {},
	_id_user_active: null,
	_id_type_user_active: null,
	_username_active: null,
	_master_account: null,
	_image_active: null,
	_master_image_active: null,
	_language: "es-ar",
	_token_authentication: "",
	_token_authentication_created: "",
	_token_authentication_expire: "",
	_token_transaction: "",
	_token_transaction_created: "",
	_token_transaction_expire: "",
	_token_push: null,
	_model: null,
	_function: null,
	_module: null,
	_start_time: 0,
	_id_sucursal: 0,
	_sucursal: "",
	_eventoJsLoad: null,

	forcePost: function (_path, _target, _parameters) {
		$("#forcedPost").remove();
		var _html = ("<form id='forcedPost' method='post' action='" + _AJAX.server + _path + "' target='" + _target + "'>");
		$.each(_parameters, function (key, value) {
			if (key == "where") { value = _TOOLS.utf8_to_b64(value); }
			_html += ("<input type='hidden' id='" + key + "' name='" + key + "' value='" + value + "'></input>");
		});
		_html += "</form>";
		$("body").append(_html);
		setTimeout(function () { $("#forcedPost").submit(); }, 1000);
	},
	formatFixedParameters: function (_json) {
		try {
			_AJAX._user_firebase.getIdToken().then(function (data) {
				_AJAX._token_push = data;
			}).catch(function (data) {
				_AJAX._token_push = "";
			});
		} catch (rex) {
			_AJAX._token_push = null;
		} finally {
			_json["id_sucursal"] = _AJAX._id_sucursal;
			_json["sucursal"] = _AJAX._sucursal;
			_json["token_push"] = _AJAX._token_push;
			_json["language"] = _AJAX._language;
			_json["token_authentication"] = _AJAX._token_authentication;
			_json["id_app"] = _AJAX._id_app;
			if (_AJAX._id_user_active == "" || _AJAX._id_user_active == null) { _AJAX._id_user_active = 0; }
			_json["id_user_active"] = _AJAX._id_user_active;
			_json["username_active"] = _AJAX._username_active;
			if (_json["id_app"] == undefined) { _json["id_app"] = _AJAX._id_app; }
			if (_json["id_type_user_active"] == undefined) { _json["id_type_user_active"] = _AJAX._id_type_user_active; }
			if (_json["id_channel"] == undefined) { _json["id_channel"] = _AJAX._id_channel; }
			if (_json["model"] == undefined) { _json["module"] = _AJAX.model; }
			if (_json["module"] == undefined) { _json["module"] = _AJAX._module; }
			if (_json["function"] == undefined) { _json["function"] = _AJAX._function; }
			if (_json["table"] == undefined) { _json["table"] = ""; }
			if (_json["method"] == undefined) { _json["method"] = uri_prefix + "api.backend/neocommand"; }
			//alert('_json["eventoJsLoad"]:*' + _json["eventoJsLoad"]+"*");
			if (_json["eventoJsLoad"] == undefined) { _json["eventoJsLoad"] = _AJAX._eventoJsLoad; }
			return _json;
		}
	},
	initialize: function (_user_firebase) {
		if (_AJAX._user_firebase == null) { _AJAX._user_firebase = _user_firebase; }
		_AJAX._ready = true;
	},
	ExecuteDirect: function (_json, _method) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX.Execute(_AJAX.formatFixedParameters(_json)).then(function (datajson) {
						if (datajson.status != undefined) {
							if (datajson.status == "OK") {
								$(".raw-username_active").html(_AJAX._username_active);
								resolve(datajson);
							} else {
								reject(datajson);
							}
						} else {
							resolve(datajson);
						}
					});
				} catch (rex) {
					reject(rex);
				}
			});
	},
	Execute: function (_json) {
		_AJAX._start_time = new Date().getTime();
		return new Promise(
			function (resolve, reject) {
				try {
					if (!_AJAX._ready) { _AJAX.initialize(null); }
					$(".raw-raw-request").html(_TOOLS.prettyPrint(_json));
					var _url = (_AJAX.server + _json.method);
					_url = _url.replace("index.php/", "");
					var ajaxRq = $.ajax({
						type: "POST",
						dataType: "json",
						url: _url,
						data: _json,
						beforeSend: function () { _AJAX.onBeforeSendExecute(); },
						complete: function () { _AJAX.onCompleteExecute(); },
						error: function (xhr, ajaxOptions, thrownError) { reject(thrownError); },
						success: function (datajson) {
							_AJAX.onSuccessExecute(datajson, _json)
								.then(function (datajson) { resolve(datajson); })
								.catch(function (err) { reject(err); });
						}
					});
				} catch (rex) {
					reject(rex);
				}
			}
		)
	},
	Load: function (_file) {
		return new Promise(
			function (resolve, reject) {
				var ajaxRq = $.ajax({
					type: "GET",
					timeout: 10000,
					dataType: "html",
					async: false,
					cache: false,
					url: _file,
					success: function (data) { resolve(data); },
					error: function (xhr, msg) { reject(msg); }
				});
			});
	},
	onBeforeSendExecute: function () {
		$(".waiter").removeClass("d-none");
		$(".wait-menu-ajax").html("<img src='" + _AJAX._pre + "./assets/img/menu.gif' style='height:24px'/>");
		$(".wait-search-ajax").html("<img src='" + _AJAX._pre + "./assets/img/search.gif' style='height:25px;width:50px;'/>");
		$(".wait-accept-ajax").html("<img src='" + _AJAX._pre + "./assets/img/accept.gif' style='height:25px;width:65px;'/>");
		if (_AJAX._waiter) {
			$(".wait-ajax").html("<img src='" + _AJAX._pre + "./assets/img/wait.gif' style='height:36px;'/>");
			$.blockUI({ message: '<img src="' + _AJAX._pre + './assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
		}
	},
	onCompleteExecute: function () {
		var request_time = ((new Date().getTime() - _AJAX._start_time) / 1000);
		$(".img-master").attr("src", _AJAX._master_image_active);
		$(".img-user").attr("src", _AJAX._image_active);
		$(".elapsed-time").html("Respuesta en " + request_time + " s");
		$(".waiter").html("");
		$(".status-ajax-calls").removeClass("d-none");
		if (_AJAX._waiter) { $.unblockUI(); }
		_AJAX._waiter = false;
	},
	onSuccessExecute: function (datajson, _json_original) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (datajson["message"] == "Records") { datajson["message"] = ""; }
					$(".raw-raw-response").html(_TOOLS.prettyPrint(datajson));
					$(".raw-message").html(datajson["code"] + ": " + datajson["message"]);
					if (datajson["status"] == "OK") {
						$(".status-last-call").removeClass("badge-danger").addClass("badge-success");
						$(".status-message").removeClass("d-sm-inline");
						//if (parseInt(_AJAX._doc_editor) == 1) { $(".editor-mode").removeClass("d-none"); } else { $(".editor-mode").addClass("d-none"); }
						//if (parseInt(_AJAX._doc_reviser) == 1) { $(".reviser-mode").removeClass("d-none"); } else { $(".reviser-mode").addClass("d-none"); }
						//if (_AJAX._doc_publisher == 1) { $(".publisher-mode").removeClass("d-none"); } else { $(".publisher-mode").addClass("d-none"); }
					} else {
						$(".status-last-call").removeClass("badge-success").addClass("badge-danger");
						$(".status-message").html(datajson["code"] + ": " + datajson["message"]).addClass("d-sm-inline");
					}
					$(".status-last-call").html(datajson["status"]);
					if (datajson == null) {
						datajson = { "results": null };
						resolve(datajson);
					} else {
						if (datajson.compressed == null) { datajson.compressed = false; }
						if (datajson.compressed == undefined) { datajson.compressed = false; }
						if (datajson != null && datajson.compressed) {
							var zip = new JSZip();
							JSZip.loadAsync(atob(datajson.message)).then(function (zip) {
								zip.file("compressed.tmp").async("string").then(
									function success(content) {
										datajson.message = content;
										resolve(datajson);
									},
									function error(err) { reject(err); });
							});
						} else {
							if (datajson.message != "") { _FUNCTIONS.onAlert({ "message": datajson.message, "class": "alert-danger" }); }
							switch (parseInt(datajson.code)) {
								case 5400:
									_AJAX.UiReAuthenticate({}).then(function (data) {
										_FUNCTIONS.onStatusAuthentication(data);
										_AJAX.Execute(_json_original);
									})
									break;
								case 5200:
								case 5401:
									var _title = (datajson.code + ": " + datajson.message);
									var _body = "<p class='text-monospace'>Ha cambiado su token de autenticación.</p>";
									_body += "<p class='text-monospace'>Esto puede haberse debido a: ";
									_body += "<li>Sus credenciales fueron usadas en otro dispositivo estando la actual sesión activa</li>";
									_body += "<li>Desde administración, se ha modificado su perfil de seguridad</li>";
									_body += "</p > ";
									_body += "<p class='text-monospace'>Por favor autentíquese nuevamente, para seguir en este dispositivo.</p>";
									_FUNCTIONS.onInfoModal({ "title": _title, "body": _body });
									_FUNCTIONS.onReloadInit();
									break;
								default:
									resolve(datajson);
									break;
							}
						}
					}
				} catch (rex) {
					reject(rex);
				}
			}
		)
	},

	UiGet: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "get";
				_AJAX._waiter = false;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSave: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "save"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiOffline: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "offline"; //function
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiOnline: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "online"; //function
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDelete: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "delete"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiProcess: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "process"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiForm: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/neocommand"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiBrow: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "brow";
				_json["method"] = uri_prefix + "api.backend/neocommand"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiEdit: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "edit";
				_json["method"] = uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiExcel: function (_json) {
		_json["mode"] = "download"; // TOque aca!
		_json["exit"] = "download";
		_json["function"] = "excel";
		_AJAX.forcePost(uri_prefix + 'api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiPdf: function (_json) {
		_json["mode"] = "view";
		_json["exit"] = "download";
		_json["function"] = "pdf";
		_AJAX.forcePost(uri_prefix + 'api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiAuthenticate: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["try"] = "LOCAL";
				//_json["try"] = "LDAP";
				_json["method"] = uri_prefix + "api.backend/authenticate"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) {
					resolve(data);
				}).catch(function (err) {
					reject(err);
				});
			});
	},
	UiReAuthenticate: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/reAuthenticate"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogged: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/logged"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogout: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/logout"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMessageRead: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "messageRead";
				_json["module"] = "mod_backend";
				_json["table"] = "messages_attached";
				_json["model"] = "messages_attached";
				_json["method"] = uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMessagesNotification: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "notifications";
				_json["module"] = "mod_backend";
				_json["table"] = "messages_attached";
				_json["model"] = "messages_attached";
				_json["method"] = uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSendExternal: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_backend";
				_json["table"] = "external";
				_json["model"] = "external";
				_json["method"] = uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogGeneral: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/logGeneral";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) {
					resolve(data);
				}).catch(function (err) {
					reject(err);
				});
			});
	},

	UiClientDetails: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/clientDetails"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiTransfersDetails: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/transfersDetails"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiTransfersDetailsExcel: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/transfersDetailsExcel"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiTransfersPostDetails: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/transfersPostDetails"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGenerarCSVManual: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/generarArchivosManuales"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiAcceptNote: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/acceptNote"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiListNotes: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/listNotes"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDeleteNote: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/deleteNote"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiManualUpload: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = uri_prefix + "api.backend/manualUpload"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

};
