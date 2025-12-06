(function () {
	var today = new Date();
	$.getScript("./assets/js/AJAX.js?" + today.toDateString()).done(function (script, textStatus) {
		$.getScript("./assets/js/TOOLS.js?" + today.toDateString()).done(function (script, textStatus) {
			$.getScript("./assets/js/FUNCTIONS.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
				moment().tz("America/Argentina/Buenos_Aires").format();
				window.addEventListener("dragover", function (e) { e = e || event; e.preventDefault(); }, false);
				window.addEventListener("drop", function (e) { e = e || event; e.preventDefault(); }, false);

				//$(document).off("keyup keypress").on("keyup keypress", function (e) {
				//	var keyCode = (e.keyCode || e.which);
				//	if (keyCode === 13) { e.preventDefault(); return false; }
				//});
				//$("body").off("keyup", ".textarea").on("keyup", ".textarea", function (e) {
				//	var keyCode = (e.keyCode || e.which);
				//	if (keyCode === 13) {
				//		$(this).val($(this).val() + "\r\n");
				//		e.preventDefault();
				//		return true;
				//	}
				//});
				$("body").off("click", ".btn-delete-note").on("click", ".btn-delete-note", function (e) {
					_FUNCTIONS.onDeleteNote($(this));
				});
				
				$("body").off("click", ".btn-note-list").on("click", ".btn-note-list", function (e) {
					_FUNCTIONS.onVerNotes($(this));
				});

				$("body").off("click", ".btn-note").on("click", ".btn-note", function (e) {
					_FUNCTIONS.onFormNote($(this));
				});
				$("body").off("click", ".btn-export-excel").on("click", ".btn-export-excel", function (e) {
					_FUNCTIONS.onPageDetailsExcel($(this));
				});
				$("body").off("change", ".cbo_id_client").on("change", ".cbo_id_client", function (e) {
					_FUNCTIONS.onClientDetails($(this));
				});
				$("body").off("click", ".btn-counters").on("click", ".btn-counters", function (e) {
					_FUNCTIONS.onClientDetails($(".cbo_id_client"));
				});
				$("body").off("click", ".btn-pager").on("click", ".btn-pager", function (e) {
					_FUNCTIONS.onPageDetails($(this).attr("data-target"), $(this).attr("data-page"));
				});
				$("body").off("click", ".btn-search-details").on("click", ".btn-search-details", function (e) {
					_FUNCTIONS.onPageDetails($(this).attr("data-target"),1);
				});
				$("body").off("click", ".btn-post-pager").on("click", ".btn-post-pager", function (e) {
					_FUNCTIONS.onPagePostDetails($(this).attr("data-page"));
				});
				$("body").off("click", ".btn-search-post").on("click", ".btn-search-post", function (e) {
					_FUNCTIONS.onPagePostDetails(1);
				});
				$("body").off("change", ".chkDates").on("change", ".chkDates", function () {
					_FUNCTIONS.onActivateDates($(this));
				});

				$("body").off("keyup", ".search-trigger").on("keyup", ".search-trigger", function (e) {
					var keyCode = (e.keyCode || e.which);
					if (keyCode === 13) { _FUNCTIONS.onBrowserSearch($(".btn-browser-search")); }
				});
				$("body").off("change", ".search-trigger").on("change", ".search-trigger", function () {
					if ($(this).is("select") === true) { _FUNCTIONS.onBrowserSearch($(".btn-browser-search")); }
				});
				$("body").off("click", ".btn-login").on("click", ".btn-login", function () {
					_FUNCTIONS.onLogin($(this)).then(function (data) { _AJAX.UiLogged({}); });
				});
			
				$("body").off("dblclick", ".record-dbl-click").on("dblclick", ".record-dbl-click", function () {
					_FUNCTIONS.onRecordEdit($(this));
				});

				$("body").off("click", ".btn-logout").on("click", ".btn-logout", function () {
					_FUNCTIONS.onLogout($(this));
				});
				$("body").off("click", ".btn-menu-open").on("click", ".btn-menu-open", function (e) {
					_FUNCTIONS.onMenuOpen($(this), e);
				});
				$("body").off("click", ".btn-menu-close").on("click", ".btn-menu-close", function (e) {
					_FUNCTIONS.onMenuClose($(this), e);
				});
				$("body").off("click", ".btn-menu-click").on("click", ".btn-menu-click", function (e) {
					console.log("btn-menu-click");
					_FUNCTIONS.onMenuClick($(this));
				});
				$("body").off("click", ".btn-record-edit").on("click", ".btn-record-edit", function (e) {
					_FUNCTIONS.onRecordEdit($(this));
				});
				$("body").off("click", ".btn-check-paycode").on("click", ".btn-check-paycode", function (e) {
					_FUNCTIONS.onCheckPaycode($(this));
				});
				$("body").off("click", ".btn-record-remove").on("click", ".btn-record-remove", function (e) {
					_FUNCTIONS.onRecordRemove($(this));
				});
				$("body").off("click", ".btn-record-offline").on("click", ".btn-record-offline", function (e) {
					_FUNCTIONS.onRecordOffline($(this));
				});
				$("body").off("click", ".btn-record-online").on("click", ".btn-record-online", function (e) {
					_FUNCTIONS.onRecordOnline($(this));
				});
				$("body").off("click", ".btn-record-process").on("click", ".btn-record-process", function (e) {
					_FUNCTIONS.onRecordProcess($(this));
				});
				$("body").off("click", ".btn-abm-accept").on("click", ".btn-abm-accept", function (e) {
					_FUNCTIONS.onAbmAccept($(this));
				});

				$("body").off("click", ".btn-abm-accept-confirm").on("click", ".btn-abm-accept-confirm", function (e) {
					try {
						if (!confirm("¿Confirma la operación?")) { return false; }
						$(".html").each(function () {
							//alert(this.name);
							$("#" + this.name).val($('.nicEdit-main').html());
						});
						//$('#ModeloNotificacionHtml').val($('.nicEdit-main').html());
					} catch (error) {
						console.error(error);
					}

					_FUNCTIONS.onAbmAccept($(this));
				});

				$("body").off("click", ".btn-abm-cancel").on("click", ".btn-abm-cancel", function (e) {
					_FUNCTIONS.onAbmCancel($(this));
				});
				$("body").off("click", ".btn-browser-search").on("click", ".btn-browser-search", function (e) {
					_FUNCTIONS.onBrowserSearch($(this));
				});
				$("body").off("click", ".btn-excel-search").on("click", ".btn-excel-search", function (e) {
					_FUNCTIONS.onBrowserSearch($(this));
				});
				$("body").off("click", ".btn-pdf-search").on("click", ".btn-pdf-search", function (e) {
					_FUNCTIONS.onBrowserSearch($(this));
				});
				$("body").off("click", ".btn-brief").on("click", ".btn-brief", function (e) {
					_FUNCTIONS.onBriefModal($(this));
				});
				$("body").off("click", ".btn-verify-signs").on("click", ".btn-verify-signs", function (e) {
					_FUNCTIONS.onVerifySigns($(this));
				});
				$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function (e) {
					$($(this).attr("data-click")).click();
					_FUNCTIONS.onDestroyModal(".modal");
				});
				$("body").off("click", ".btn-upload").on("click", ".btn-upload", function (e) {
					$($(this).attr("data-click")).click();
				});
				$("body").off("click", ".btnUploadFiles").on("click", ".btnUploadFiles", function (e) {
					$(".btnPickFiles").click();
				});
				$("body").off("click", ".btnDeleteFile").on("click", ".btnDeleteFile", function (e) {
					_FUNCTIONS.onDeleteFile($(this));
				});
				$("body").off("click", ".btnSendFilesToServer").on("click", ".btnSendFilesToServer", function (e) {
					_FUNCTIONS.onSendFilesToServer($(this));
				});

				$("body").off("click", ".btn-upload-reset").on("click", ".btn-upload-reset", function (e) {
					_FUNCTIONS.onResetSelectedFile($(this));
				});
				$("body").off("click", ".btn-upload-delete").on("click", ".btn-upload-delete", function (e) {
					_FUNCTIONS.onDeleteSelectedFile($(this));
				});
				$("body").off("change", ".btn-pick-files-image").on("change", ".btn-pick-files-image", function (e) {
					_FUNCTIONS.onProcessSelectedFiles($(this));
				});
				$("body").off("change", ".btnPickFiles").on("change", ".btnPickFiles", function (e) {
					_FUNCTIONS.onSelectedFiles($(this));
				});
				$("body").off("change", ".btn-pick-files-image_apaisada").on("change", ".btn-pick-files-image_apaisada", function (e) {
					_FUNCTIONS.onProcessSelectedFiles($(this));
				});
				$("body").off("change", ".btn-folders-files-folders").on("change", ".btn-folders-files-folders", function (e) {
					_FUNCTIONS.onProcessSelectedFilesFolders($(this));
				});
				$("body").off("change", ".chkToggleNotas").on("change", ".chkToggleNotas", function (e) {
					if ($(this).prop("checked")) {
						$(".row-note").addClass("d-none");
						$(".row-hasnote").removeClass("d-none");
					} else {
						$(".row-note").removeClass("d-none");
					}
				});
				

				$("body").off("change", ".id_type_command").on("change", ".id_type_command", function (e) {
					_FUNCTIONS.onTypeCommandChange($(this));
				});
				$("body").off("click", ".btn-message-external").on("click", ".btn-message-external", function (e) {
					_FUNCTIONS.onFolderMessagesModal($(this));
				});
				$("body").off("click", ".btn-message-read").on("click", ".btn-message-read", function (e) {
					_FUNCTIONS.onMessageRead($(this));
				});
				$("body").off("click", ".btn-record-check").on("click", ".btn-record-check", function (e) {
					_FUNCTIONS.onCheckRecord($(this));
				});
				$("body").off("click", ".btn-external-link").on("click", ".btn-external-link", function () {
					_FUNCTIONS.onAddLinkExternal($(this));
				});
				$("body").off("click", ".btn-external-link1").on("click", ".btn-external-link1", function () {
					_FUNCTIONS.onAddLinkExternal1($(this));
				});
				$("body").off("click", ".btn-receipt-search").on("click", ".btn-receipt-search", function (e) {
					_FUNCTIONS.onReceiptSearch($(this));
				});
				$("body").off("click", ".btn-csv-manual").on("click", ".btn-csv-manual", function (e) {
					_FUNCTIONS.onCSVManual($(this));
				});
			});
		});
	});
})();

