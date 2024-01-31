<script type="text/javascript">
	function edit_basicinfo() {

		$("#edit-basicinfo").hide();
		$("#update-basicinfo").show();
		$(".inputForm").prop("disabled", false);
	}

	function edit_family() {

		$("#edit-family").hide();
		$("#update-family").show();
		$(".inputForm").prop("disabled", false);
	}

	function edit_contact() {

		$("#edit-contact").hide();
		$("#update-contact").show();
		$(".inputForm").prop("disabled", false);
	}

	function edit_educ() {

		$("#edit-educ").hide();
		$("#update-educ").show();
		$(".inputForm").prop("disabled", false);
	}

	function edit_skills() {

		$("#edit-skills").hide();
		$("#update-skills").show();
		$(".inputForm").prop("disabled", false);
	}

	function edit_apphis() {

		$("#edit-apphis").hide();
		$("#update-apphis").show();
		$(".inputForm").prop("disabled", false);
	}

	function update(code) {

		var empId = $("[name = 'empId']").val();

		$.alert.open({
			type: 'warning',
			cancel: false,
			content: "Are you sure to save the new update?",
			buttons: {
				OK: 'Yes',
				NO: 'Not now'
			},

			callback: function(button) {
				if (button == 'OK') {

					if (code == "update-basicinfo") {

						var fname = $("[name = 'fname']").val();
						var mname = $("[name = 'mname']").val();
						var lname = $("[name = 'lname']").val();
						var suffix = $("[name = 'suffix']").val();
						var datebirth = $("[name = 'datebirth']").val();
						var citizenship = $("[name = 'citizenship']").val();
						var gender = $("[name = 'gender']").val();
						var civilstatus = $("[name = 'civilstatus']").val();
						var religion = $("[name = 'religion']").val();
						var bloodtype = $("[name = 'bloodtype']").val();
						var weight = $("[name = 'weight']").val();
						var height = $("[name = 'height']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								fname: fname,
								mname: mname,
								lname: lname,
								suffix: suffix,
								datebirth: datebirth,
								citizenship: citizenship,
								gender: gender,
								civilstatus: civilstatus,
								religion: religion,
								bloodtype: bloodtype,
								weight: weight,
								height: height
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('basicinfo');
							}
						});
					} else if (code == "update-family") {

						var mother = $("[name = 'mother']").val();
						var father = $("[name = 'father']").val();
						var guardian = $("[name = 'guardian']").val();
						var spouse = $("[name = 'spouse']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								mother: mother,
								father: father,
								guardian: guardian,
								spouse: spouse
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('family');
							}
						});
					} else if (code == "update-contact") {

						var homeaddress = $("[name = 'homeaddress']").val();
						var cityaddress = $("[name = 'cityaddress']").val();
						var contactperson = $("[name = 'contactperson']").val();
						var contactpersonadd = $("[name = 'contactpersonadd']").val();
						var contactpersonno = $("[name = 'contactpersonno']").val();
						var cellphone = $("[name = 'cellphone']").val();
						var telno = $("[name = 'telno']").val();
						var email = $("[name = 'email']").val();
						var fb = $("[name = 'fb']").val();
						var twitter = $("[name = 'twitter']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								homeaddress: homeaddress,
								cityaddress: cityaddress,
								contactperson: contactperson,
								contactpersonadd: contactpersonadd,
								contactpersonno: contactpersonno,
								cellphone: cellphone,
								telno: telno,
								email: email,
								fb: fb,
								twitter: twitter
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('contact');
							}
						});
					} else if (code == "update-educ") {

						var attainment = $("[name = 'attainment']").val();
						var school = $("[name = 'school']").val();
						var course = $("[name = 'course']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								attainment: attainment,
								school: school,
								course: course
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('educ');
							}
						});
					} else if (code == "update-skills") {

						var hobbies = $("[name = 'hobbies']").val();
						var skills = $("[name = 'skills']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								hobbies: hobbies,
								skills: skills
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('skills');
							}
						});
					} else if (code == "update-apphis") {

						var posApplied = $("[name = 'posApplied']").val();
						var dateApplied = $("[name = 'dateApplied']").val();
						var dateExamined = $("[name = 'dateExamined']").val();
						var examResult = $("[name = 'examResult']").val();
						var dateBrief = $("[name = 'dateBrief']").val();
						var dateHired = $("[name = 'dateHired']").val();
						var aeRegular = $("[name = 'aeRegular']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								posApplied: posApplied,
								dateApplied: dateApplied,
								dateExamined: dateExamined,
								examResult: examResult,
								dateBrief: dateBrief,
								dateHired: dateHired,
								aeRegular: aeRegular
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('application');
							}
						});
					} else if (code == "update-benefits") {

						var ph = $("[name = 'ph']").val();
						var sss = $("[name = 'sss']").val();
						var pagibig = $("[name = 'pagibig']").val();
						var pagibigrtn = $("[name = 'pagibigrtn']").val();
						var tinno = $("[name = 'tinno']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								ph: ph,
								sss: sss,
								pagibig: pagibig,
								pagibigrtn: pagibigrtn,
								tinno: tinno
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('benefits');
							}
						});
					} else if (code == "update-remarks") {

						var remarks = $("[name = 'remarks']").val();

						$.ajax({
							type: "POST",
							url: "employee_information_details.php?request=" + code,
							data: {
								empId: empId,
								remarks: remarks
							},
							success: function(data) {

								if (data.trim() == "success") {
									succSave("Updating Sucessful!");
								} else {
									alert(data);
								}

								getdefault('remarks');
							}
						});
					}
				}
			}
		});
	}

	function add_seminar(no) {

		$("#addSeminar").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addSeminar").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addSeminar",
			data: {
				empId: empId,
				no: no
			},
			success: function(data) {

				$(".addSeminar").html(data);
			}
		});
	}

	function viewSeminarCert(no) {

		$("#viewSeminar").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#viewSeminar").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=seminarCertificate",
			data: {
				no: no
			},
			success: function(data) {

				data = data.trim();
				$(".viewSeminar").html("<center><img class='img-responsive' src='" + data + "' alt='Photo'></center>");
			}
		});
	}

	function add_charref(no) {

		$("#addCharRef").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addCharRef").modal("show");
		var empId = $("[name = 'empId']").val();
		$("button#add-charref").prop("disabled", false);

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addCharRef",
			data: {
				empId: empId,
				no: no
			},
			success: function(data) {

				$(".addCharRef").html(data);
			}
		});
	}

	function submitCharRef() {

		var no = $("[name = 'no']").val();
		var empId = $("[name = 'appId']").val();
		var charName = $("[name = 'charName']").val();
		var charCompanyLocation = $("[name = 'charCompanyLocation']").val();
		var charPosition = $("[name = 'charPosition']").val();
		var charContact = $("[name = 'charContact']").val();

		if (charName == "" || charCompanyLocation == "" || charPosition == "") {

			$.alert.open({
				type: 'warning',
				cancel: false,
				content: "Please Fill-up Required Fields!",
				buttons: {
					OK: 'Ok'
				},

				callback: function(button) {
					if (button == 'OK') {

						if (charName == "") {

							$("[name = 'charName']").css("border-color", "#dd4b39");
						}

						if (charCompanyLocation == "") {

							$("[name = 'charCompanyLocation']").css("border-color", "#dd4b39");
						}

						if (charPosition == "") {

							$("[name = 'charPosition']").css("border-color", "#dd4b39");
						}
					}
				}
			});

		} else {

			$("button#submitCharRef").prop("disabled", true);

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=submitCharRef",
				data: {
					empId: empId,
					no: no,
					charName: charName,
					charCompanyLocation: charCompanyLocation,
					charPosition: charPosition,
					charContact: charContact
				},
				success: function(data) {

					response = data.split("||");

					if (response[0].trim() == "success") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Character Reference Details Successfully " + response[1].trim(),
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#addCharRef").modal("hide");
									getdefault('charref');
								}

							}
						});
					} else {

						alert(response);
					}
				}
			});
		}
	}

	function viewAppraisalDetails(detailsId) {

		$("#promoAppraisalDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#promoAppraisalDetails").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=appraisalDetails",
			data: {
				detailsId: detailsId
			},
			success: function(data) {

				$(".promoAppraisalDetails").html(data);
			}
		});
	}

	function previewAppraisalDetails(detailsId) {

		$("#appraisalDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#appraisalDetails").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=appraisalDetails",
			data: {
				detailsId: detailsId
			},
			success: function(data) {

				$(".appraisalDetails").html(data);
			}
		});
	}

	function viewExam(examVal) {

		$("#examDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#examDetails").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=examDetails",
			data: {
				examVal: examVal
			},
			success: function(data) {

				$(".examDetails").html(data);
			}
		});
	}

	function viewAppDetails(empId) {

		$("#appHistDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#appHistDetails").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=appHistDetails",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".appHistDetails").html(data);
			}
		});
	}

	function viewInterview(empId) {

		$("#interviewDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#interviewDetails").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=interviewDetails",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".interviewDetails").html(data);
			}
		});
	}

	function viewPromoDetails(contract, recordNo, empId) {

		$("#contractDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#contractDetails").modal("show");
		$(".contractDetails").html("");
		$(".loading-gif").html('<center><img src="images/icon/circle.gif" width="60%" height="60%"></center>');

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=contractPromoDetails",
			data: {
				contract: contract,
				recordNo: recordNo,
				empId: empId
			},
			success: function(data) {

				$(".loading-gif").html("");
				$(".contractDetails").html(data);
			}
		});
	}

	function viewDetails(contract, recordNo, empId) {

		$("#contractDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#contractDetails").modal("show");
		$("#loading-gif").show();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=contractDetails",
			data: {
				contract: contract,
				recordNo: recordNo,
				empId: empId
			},
			success: function(data) {

				$("#loading-gif").hide();
				$(".contractDetails").html(data);
			}
		});
	}

	function viewFile(file, table, field, empId, recordNo) {

		$("#viewFile").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#viewFile").modal("show");
		$(".viewFile").html("");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=" + file,
			data: {
				table: table,
				field: field,
				empId: empId,
				recordNo: recordNo
			},
			success: function(data) {

				data = data.trim();
				$(".viewFile").html("<center><img class='img-responsive' src='" + data + "' alt='Photo'></center>");
			}
		});
	}

	function updatePromoDetails(contract, recordNo, empId) {

		$("#updateContract").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#updateContract").modal("show");
		// $(".updateContract").html("");  

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=editContract",
			data: {
				contract: contract,
				recordNo: recordNo,
				empId: empId
			},
			success: function(data) {

				$(".updateContract").html(data);
			}
		});
	}

	function updateDetails(contract, recordNo, empId) {

		$("#updateContractDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#updateContractDetails").modal("show");
		// $(".updateContractDetails").html("");  

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=editContractDetails",
			data: {
				contract: contract,
				recordNo: recordNo,
				empId: empId
			},
			success: function(data) {

				$(".updateContractDetails").html(data);
			}
		});
	}

	function addPromoContract() {

		var empId = $("[name = 'empId']").val();
		var agency = $("[name = 'agency']").val();
		var company = $("[name = 'company']").val();
		var promoType = $("[name = 'promoType']").val();
		var department = $("[name = 'department']").val();
		var startdate = $("[name = 'startdate']").val();
		var eocdate = $("[name = 'eocdate']").val();
		var duration = $("[name = 'duration']").val();
		var position = $("[name = 'position']").val();
		var empType = $("[name = 'empType']").val();
		var contractType = $("[name = 'contractType']").val();
		var current_status = $("[name = 'current_status']").val();
		var remarks = $("[name = 'remarks']").val();
		var store = "";

		if (promoType == "STATION") {

			var loop = $("[name = 'loop']").val();

			for (var i = 1; i <= loop; i++) {

				if ($("#radio_" + i).is(':checked')) {

					store = $("#radio_" + i).val();
				}
			}

		} else {

			var chkNum = 0;
			var counter = $("[name = 'counter']").val();

			for (var i = 1; i <= counter; i++) {

				if ($("#check_" + i).is(':checked')) {

					chkNum++;
					var bunit_id = $("#check_" + i).val();
					store += bunit_id + "||";
				}
			}
		}

		if (store == "" || (promoType == "ROVING" && chkNum < 2) || company == "" || promoType == "" || department == "" || startdate == "" || eocdate == "" || position == "" || empType == "" || current_status == "") {

			if (store == "") {

				errDup("Please Select Business Unit!");
			} else if (promoType == "ROVING" && chkNum < 2) {

				errDup("Please Add Another Business Unit for Setup!");
			} else {

				$.alert.open({
					type: 'warning',
					cancel: false,
					content: "Please Fill-up Required Fields!",
					buttons: {
						OK: 'Ok'
					},

					callback: function(button) {
						if (button == 'OK') {

							if (company == "") {

								$("[name = 'company']").css("border-color", "#dd4b39");
							}

							if (promoType == "") {

								$("[name = 'promoType']").css("border-color", "#dd4b39");
							}

							if (department == "") {

								$("[name = 'department']").css("border-color", "#dd4b39");
							}

							if (startdate == "") {

								$("[name = 'startdate']").css("border-color", "#dd4b39");
							}

							if (eocdate == "") {

								$("[name = 'eocdate']").css("border-color", "#dd4b39");
							}

							if (position == "") {

								$("[name = 'position']").css("border-color", "#dd4b39");
							}

							if (empType == "") {

								$("[name = 'empType']").css("border-color", "#dd4b39");
							}

							if (current_status == "") {

								$("[name = 'current_status']").css("border-color", "#dd4b39");
							}
						}
					}
				});
			}
		} else {

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=addPromoContract",
				data: {
					empId: empId,
					agency: agency,
					company: company,
					promoType: promoType,
					department: department,
					startdate: startdate,
					eocdate: eocdate,
					duration: duration,
					position: position,
					empType: empType,
					contractType: contractType,
					current_status: current_status,
					remarks: remarks,
					store: store
				},
				success: function(data) {

					data = data.trim();
					if (data == "success") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Contract History Successfully Added",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#addContractDetails").modal("hide");
									getdefault('employment');
								}

							}
						});
					} else if (data == "duplicate") {

						$.alert.open({
							type: 'warning',
							cancel: false,
							content: "Cannot be Added! <br> This contract already exist.",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									// $("#addContractDetails").modal("hide");
									// getdefault('employment');
								}

							}
						});
					} else if (data == "active") {

						$.alert.open({
							type: 'warning',
							cancel: false,
							content: "Active status, cannot be added!",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									// $("#addContractDetails").modal("hide");
									// getdefault('employment');
								}

							}
						});
					} else {

						errDup("There's an error in the system!");
					}
				}
			});
		}
	}

	function startdate() {

		$("[name = 'startdate']").css("border-color", "#ccc");
		$("[name = 'eocdate']").val("");
	}

	function durationContract(eocdate) {

		var dF = $("[name = 'startdate']").val();
		var dT = eocdate;
		$("[name = 'eocdate']").css("border-color", "#ccc");

		if (dF == "") {

			$.alert.open({
				type: 'warning',
				cancel: false,
				content: "Please Fill-up Startdate First!",
				buttons: {
					OK: 'Ok'
				},

				callback: function(button) {
					if (button == 'OK') {

						$("[name = 'eocdate']").val("");
					}
				}
			});
		} else {

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=durationContract",
				data: {
					dF: dF,
					dT: dT
				},
				success: function(data) {

					data = data.trim();
					data = data.split("||");

					if (data[0] == "Ok") {

						$("[name = 'duration']").val(data[1]);
					} else {

						$.alert.open({
							type: 'warning',
							cancel: false,
							content: data,
							buttons: {
								OK: 'Ok'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("[name = 'eocdate']").val("");
								}
							}
						});
					}
				}
			});
		}
	}

	function locateBunit(promoType) {

		$("[name = 'promoType']").css("border-color", "#ccc");
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=locateBunit",
			data: {
				promoType: promoType
			},
			success: function(data) {

				$(".store").html(data);
			}
		});

	}

	function locate_vendor(department) {

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=locate_vendor",
			data: {
				department: department
			},
			success: function(data) {

				$("[name = 'vendor']").html(data);
			}
		});
	}

	function locateDeptS(id) {

		$("[name = 'store']").val(id);

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=locateDeptS",
			data: {
				id: id
			},
			success: function(data) {

				$("[name = 'department']").html(data);
			}
		});
	}

	function locateDeptR() {

		var storeId = "";
		var counter = $("[name = 'counter']").val()
		for (var i = 1; i <= counter; i++) {

			if ($("#check_" + i).is(':checked')) {

				var bunit_id = $("#check_" + i).val();
				storeId += bunit_id + "||";
			}
		}

		$("[name = 'store']").val(storeId);

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=locateDeptR",
			data: {
				storeId: storeId
			},
			success: function(data) {

				$("[name = 'department']").html(data);
			}
		});
	}

	function updateContract() {

		var contract = $("[name = 'contract']").val();
		var recordNo = $("[name = 'recordNo']").val();
		var empId = $("[name = 'empId']").val();

		var agency = $("[name = 'agency']").val();
		var company = $("[name = 'company']").val();
		var promoType = $("[name = 'promoType']").val();
		var department = $("[name = 'department']").val();
		var vendor = $("[name = 'vendor']").val();
		var startdate = $("[name = 'startdate']").val();
		var eocdate = $("[name = 'eocdate']").val();
		var duration = $("[name = 'duration']").val();
		var position = $("[name = 'position']").val();
		var empType = $("[name = 'empType']").val();
		var contractType = $("[name = 'contractType']").val();
		var current_status = $("[name = 'current_status']").val();
		var cutoff = $("select[name = 'cutoff']").val();
		var remarks = $("[name = 'remarks']").val();
		var store = "";
		var product = $("[name = 'product']").val();

		if (promoType == "STATION") {

			var loop = $("[name = 'loop']").val();

			for (var i = 1; i <= loop; i++) {

				if ($("#radio_" + i).is(':checked')) {

					store = $("#radio_" + i).val();
				}
			}

		} else {

			var chkNum = 0;
			var counter = $("[name = 'counter']").val();

			for (var i = 1; i <= counter; i++) {

				if ($("#check_" + i).is(':checked')) {

					chkNum++;
					var bunit_id = $("#check_" + i).val();
					store += bunit_id + "||";
				}
			}
		}

		if (store == "" || (promoType == "ROVING" && chkNum < 2) || company == "" || promoType == "" || department == "" || startdate == "" || eocdate == "" || position == "" || empType == "" || current_status == "" || cutoff == "") {

			if (store == "") {

				errDup("Please Select Business Unit!");
			} else if (promoType == "ROVING" && chkNum < 2) {

				errDup("Please Add Another Business Unit for Setup!");
			} else {

				$.alert.open({
					type: 'warning',
					cancel: false,
					content: "Please Fill-up Required Fields!",
					buttons: {
						OK: 'Ok'
					},

					callback: function(button) {
						if (button == 'OK') {

							if (company == "") {

								$("[name = 'company']").css("border-color", "#dd4b39");
							}

							if (promoType == "") {

								$("[name = 'promoType']").css("border-color", "#dd4b39");
							}

							if (department == "") {

								$("[name = 'department']").css("border-color", "#dd4b39");
							}

							if (startdate == "") {

								$("[name = 'startdate']").css("border-color", "#dd4b39");
							}

							if (eocdate == "") {

								$("[name = 'eocdate']").css("border-color", "#dd4b39");
							}

							if (position == "") {

								$("[name = 'position']").css("border-color", "#dd4b39");
							}

							if (empType == "") {

								$("[name = 'empType']").css("border-color", "#dd4b39");
							}

							if (current_status == "") {

								$("[name = 'current_status']").css("border-color", "#dd4b39");
							}

							if (cutoff == "") {

								$("select[name = 'cutoff']").css("border-color", "#dd4b39");
							}
						}
					}
				});
			}
		} else {


			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=updateContract",
				data: {
					contract: contract,
					recordNo: recordNo,
					empId: empId,
					agency: agency,
					company: company,
					promoType: promoType,
					department: department,
					vendor: vendor,
					product: product,
					startdate: startdate,
					eocdate: eocdate,
					duration: duration,
					position: position,
					empType: empType,
					contractType: contractType,
					current_status: current_status,
					cutoff: cutoff,
					remarks: remarks,
					store: store
				},
				success: function(data) {

					data = data.trim();
					if (data == "success") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Contract History Successfully Updated",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#updateContract").modal("hide");
									getdefault('employment');
								}

							}
						});
					} else {

						alert(data);
					}
				}
			});
		}

	}

	function uploadPromoScannedFile(contract, recordNo, empId) {

		$("#uploadPromoScannedFile").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#uploadPromoScannedFile").modal("show");
		$(".uploadPromoScannedFile").html("");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=uploadPromoScannedFileForm",
			data: {
				contract: contract,
				recordNo: recordNo,
				empId: empId
			},
			success: function(data) {

				$(".uploadPromoScannedFile").html(data);
			}
		});
	}

	function uploadScannedFile(contract, recordNo, empId) {

		$("#uploadScannedFile").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#uploadScannedFile").modal("show");
		$(".uploadScannedFile").html("");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=uploadScannedFileForm",
			data: {
				contract: contract,
				recordNo: recordNo,
				empId: empId
			},
			success: function(data) {

				$(".uploadScannedFile").html(data);
			}
		});
	}

	function changePhoto(file, photoid, change) {

		$.alert.open({
			type: 'warning',
			cancel: false,
			content: "You are attempting to change the uploaded " + file + ", <br> Click OK to proceed.",
			buttons: {
				OK: 'Ok',
				NO: 'Not now'
			},

			callback: function(button) {
				if (button == 'OK') {

					$('#' + change).hide();
					$('#' + photoid).show();
				}

			}
		});
	}

	function readURL(input, upload) {

		$('#clear' + upload).show();
		var res = validateForm(upload);

		if (res != 1) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#photo' + upload).attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		} else {
			$('#clear' + upload).hide();
			$('#photo' + upload).removeAttr('src');
		}
	}

	function validateForm(imgid) {
		var img = $("#" + imgid).val();
		var res = '';
		var i = img.length - 1;
		while (img[i] != ".") {
			res = img[i] + res;
			i--;
		}

		//checks the file format
		if (res != "PNG" && res != "jpg" && res != "JPG" && res != "png") {
			$("#" + imgid).val("");
			errDup('Invalid File Format. Take note on the allowed file!');
			return 1;
		}

		//checks the filesize- should not be greater than 2MB
		var uploadedFile = document.getElementById(imgid);
		var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
		if (fileSize == false) {
			$("#" + imgid).val("");
			errDup('The size of the file exceeds 2MB!')
			return 1;
		}
	}

	function clears(file, preview, clrbtn) {

		$("#" + file).val("");
		$('#' + preview).removeAttr('src');
		$('#' + clrbtn).hide();
	}

	function updateContractDetails() {

		var contract = $("[name = 'contract']").val();
		var empId = $("[name = 'empId']").val();
		var recordNo = $("[name = 'recordNo']").val();

		var company = $("[name = 'company']").val();
		var businessUnit = $("[name = 'businessUnit']").val();
		var department = $("[name = 'department']").val();
		var section = $("[name = 'section']").val();
		var subSection = $("[name = 'subSection']").val();
		var unit = $("[name = 'unit']").val();

		var startdate = $("[name = 'startdate']").val();
		var eocdate = $("[name = 'eocdate']").val();
		var position = $("[name = 'position']").val();
		var empType = $("[name = 'empType']").val();
		var current_status = $("[name = 'current_status']").val();
		var posLevel = $("[name = 'posLevel']").val();

		var lodging = $("[name = 'lodging']").val();
		var posDesc = $("[name = 'posDesc']").val();
		var remarks = $("[name = 'remarks']").val();

		if (company == "" || businessUnit == "" || department == "" || startdate == "" || eocdate == "" || position == "" || empType == "" || current_status == "") {

			$.alert.open({
				type: 'warning',
				cancel: false,
				content: "Please Fill-up Required Fields!",
				buttons: {
					OK: 'Ok'
				},

				callback: function(button) {
					if (button == 'OK') {

						if (company == "") {

							$("[name = 'company']").css("border-color", "#dd4b39");
						}

						if (businessUnit == "") {

							$("[name = 'businessUnit']").css("border-color", "#dd4b39");
						}

						if (department == "") {

							$("[name = 'department']").css("border-color", "#dd4b39");
						}

						if (startdate == "") {

							$("[name = 'startdate']").css("border-color", "#dd4b39");
						}

						if (eocdate == "") {

							$("[name = 'eocdate']").css("border-color", "#dd4b39");
						}

						if (position == "") {

							$("[name = 'position']").css("border-color", "#dd4b39");
						}

						if (empType == "") {

							$("[name = 'empType']").css("border-color", "#dd4b39");
						}

						if (current_status == "") {

							$("[name = 'current_status']").css("border-color", "#dd4b39");
						}
					}
				}
			});
		} else {

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=updateContractDetails",
				data: {
					contract: contract,
					empId: empId,
					recordNo: recordNo,
					company: company,
					businessUnit: businessUnit,
					department: department,
					section: section,
					subSection: subSection,
					unit: unit,
					startdate: startdate,
					eocdate: eocdate,
					position: position,
					empType: empType,
					current_status: current_status,
					posLevel: posLevel,
					lodging: lodging,
					posDesc: posDesc,
					remarks: remarks
				},
				success: function(data) {

					data = data.trim();
					if (data == "success") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Contract History Successfully Updated!",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#updateContractDetails").modal("hide");
									getdefault('employment');
								}

							}
						});
					} else {

						alert(data);
					}
				}
			});
		}
	}

	function add_contract() {

		$("#addContractDetails").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addContractDetails").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addContract",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".addContractDetails").html(data);
			}
		});
	}

	function add_emphis(no) {

		$("#addEmploymentHist").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addEmploymentHist").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addEmploymentHist",
			data: {
				empId: empId,
				no: no
			},
			success: function(data) {

				$(".addEmploymentHist").html(data);
			}
		});
	}

	function viewEmpCert(no) {

		$("#viewEmploymentCert").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#viewEmploymentCert").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=employmentCertificate",
			data: {
				no: no
			},
			success: function(data) {

				data = data.trim();
				$(".viewEmploymentCert").html("<center><img class='img-responsive' src='" + data + "' alt='Photo'></center>");
			}
		});
	}

	function viewJobTrans(jobTransfer) {

		$("#viewJobTransfer").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#viewJobTransfer").modal("show");

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=viewJobTrans",
			data: {
				jobTransfer: jobTransfer
			},
			success: function(data) {

				$(".viewJobTransfer").html(data);
			}
		});
	}

	function add_blacklist(no) {

		$("#addBlacklist").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addBlacklist").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addBlacklist",
			data: {
				no: no,
				empId: empId
			},
			success: function(data) {

				$(".addBlacklist").html(data);
			}
		});
	}

	function nameSearch(key) {

		var str = key.trim();
		$(".search-results").hide();
		if (str == '') {
			$(".search-results-loading").slideUp(100);
		} else {
			$.ajax({
				type: "POST",
				url: "functionquery.php?request=findHrStaff",
				data: {
					str: str
				},
				success: function(data) {

					if (data != "") {
						$(".search-results").show().html(data);
					} else {

						$(".search-results").hide();
					}
				}
			});
		}
	}

	function getEmpId(supId) {

		$("[name='reportedBy']").val(supId);
		$(".search-results").hide();
		$("[name = 'reportedBy']").css("border-color", "#ccc");
	}

	function submitBlacklist() {

		var no = $("[name = 'no']").val();
		var empId = $("[name = 'empId']").val();
		var reason = $("[name = 'reason']").val().trim();
		var dateBlacklisted = $("[name = 'dateBlacklisted']").val();
		var birthday = $("[name = 'birthday']").val();
		var reportedBy = $("[name = 'reportedBy']").val().split("*");
		var address = $("[name = 'address']").val();

		if (reason == "" || dateBlacklisted == "" || reportedBy[1] == "") {

			$.alert.open({
				type: 'warning',
				cancel: false,
				content: "Please Fill-up Required Fields!",
				buttons: {
					OK: 'Ok'
				},

				callback: function(button) {
					if (button == 'OK') {

						if (reason == "") {

							$("[name = 'reason']").css("border-color", "#dd4b39");
						}

						if (dateBlacklisted == "") {

							$("[name = 'dateBlacklisted']").css("border-color", "#dd4b39");
						}

						if (reportedBy == "") {

							$("[name = 'reportedBy']").css("border-color", "#dd4b39");
						}
					}

				}
			});
		} else {

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=submitBlacklist",
				data: {
					no: no,
					empId: empId,
					reason: reason,
					dateBlacklisted: dateBlacklisted,
					birthday: birthday,
					reportedBy: reportedBy[1].trim(),
					address: address
				},
				success: function(data) {

					data = data.split("||");
					if (data[0].trim() == "success") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Blacklist History Successfully " + data[1].trim(),
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#addBlacklist").modal("hide");
									getdefault('blacklist');
								}

							}
						});
					} else {

						alert(data);
					}
				}
			});
		}
	}

	function edit_benefits() {

		$(".inputForm").prop("disabled", false);
		$("#edit-benefits").hide();
		$("#update-benefits").show();
	}

	function removeSubordinates(recordNo) {

		$.alert.open({
			type: 'warning',
			cancel: false,
			content: "Are you sure you want to remove this supervisor?",
			buttons: {
				OK: 'Yes',
				NO: 'Not now'
			},

			callback: function(button) {
				if (button == 'OK') {

					$.ajax({
						type: "POST",
						url: "employee_information_details.php?request=removeSubordinates",
						data: {
							recordNo: recordNo
						},
						success: function(data) {

							data = data.trim();

							if (data == "success") {

								$.alert.open({
									type: 'warning',
									title: 'Info',
									icon: 'confirm',
									cancel: false,
									content: "Promo Supervisor Successfully Removed!",
									buttons: {
										OK: 'Yes'
									},

									callback: function(button) {
										if (button == 'OK') {

											$("#remove_" + recordNo).fadeOut(1000, function() {

												getdefault('pss');
											});
										}
									}
								});
							}
						}
					});
				}
			}
		});
	}

	function add_supervisor() {

		$("#addSupervisor").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addSupervisor").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addSupervisor",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".addSupervisor").html(data);
			}
		});
	}

	//get company details
	function loadBusinessUnit(id) {

		$("[name = 'company']").css('border-color', '#ccc');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=businessUnit",
			data: {
				id: id
			},
			success: function(data) {

				$("[name='businessUnit']").html(data);
				$("[name='department']").val('');
				$("[name='section']").val('');
				$("[name='subSection']").val('');
				$("[name='unit']").val('');
			}
		});

		if (id.trim() != "") {

			$(".loading-gif").html('<center><img src="images/icon/circle.gif" width="60%" height="60%"></center>');
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=selectSupervisor",
				data: {
					id: id,
					loc: "cc"
				},
				success: function(data) {

					$(".loading-gif").html("");
					$(".supervisor").html(data);
				}
			});
		}
	}

	function loadDepartment(id) {

		$("[name = 'businessUnit']").css('border-color', '#ccc');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=department",
			data: {
				id: id
			},
			success: function(data) {

				$("[name='department']").html(data);
				$("[name='section']").val('');
				$("[name='subSection']").val('');
				$("[name='unit']").val('');
			}
		});

		if (id.trim() != "") {

			$(".loading-gif").html('<center><img src="images/icon/circle.gif" width="60%" height="60%"></center>');
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=selectSupervisor",
				data: {
					id: id,
					loc: "bc"
				},
				success: function(data) {

					$(".loading-gif").html("");
					$(".supervisor").html(data);
				}
			});
		}
	}

	function loadSection(id) {

		$("[name = 'department']").css('border-color', '#ccc');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=section",
			data: {
				id: id
			},
			success: function(data) {

				$("[name='section']").html(data);
				$("[name='subSection']").val('');
				$("[name='unit']").val('');
			}
		});

		if (id.trim() != "") {

			$(".loading-gif").html('<center><img src="images/icon/circle.gif" width="60%" height="60%"></center>');
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=selectSupervisor",
				data: {
					id: id,
					loc: "dc"
				},
				success: function(data) {

					$(".loading-gif").html("");
					$(".supervisor").html(data);
				}
			});
		}
	}

	function loadSubSection(id) {

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=subSection",
			data: {
				id: id
			},
			success: function(data) {

				$("[name='subSection']").html(data);
				$("[name='unit']").val('');
			}
		});

		if (id.trim() != "") {

			$(".loading-gif").html('<center><img src="images/icon/circle.gif" width="60%" height="60%"></center>');
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=selectSupervisor",
				data: {
					id: id,
					loc: "sc"
				},
				success: function(data) {

					$(".loading-gif").html("");
					$(".supervisor").html(data);
				}
			});
		}
	}

	function loadUnit(id) {

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=unit",
			data: {
				id: id
			},
			success: function(data) {

				$("[name='unit']").html(data);
			}
		});

		if (id.trim() != "") {

			$(".loading-gif").html('<center><img src="images/icon/circle.gif" width="60%" height="60%"></center>');
			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=selectSupervisor",
				data: {
					id: id,
					loc: "ssc"
				},
				success: function(data) {

					$(".loading-gif").html("");
					$(".supervisor").html(data);
				}
			});
		}
	}

	function submitSupervisor() {

		var empId = $("[name = 'empId']").val();
		var chk = $("[name = 'chkempId[]']");

		var newCHK = "";
		var chkNum = 0;
		for (var i = 0; i < chk.length; i++) {

			if (chk[i].checked == true) {

				chkNum++;
				newCHK += chk[i].value + "*";
			}
		}

		if (chkNum == 0) {

			errDup("You need to check a supervisor to be added!");

		} else {

			$.ajax({
				type: "POST",
				url: "employee_information_details.php?request=saveSupervisor",
				data: {
					empId: empId,
					newCHK: newCHK
				},
				success: function(data) {

					data = data.trim();
					if (data == "success") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Supervisor(s) Successfully Added",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#addSupervisor").modal("hide");
									getdefault('pss');
								}

							}
						});
					} else {

						alert(data);
					}
				}
			});
		}
	}

	function chkIdC(supId) {

		if ($(".chkIdC_" + supId).is(':checked')) {

			$(".chkId_" + supId).prop("checked", true);
		} else {

			$(".chkId_" + supId).prop("checked", false);
		}
	}

	function edit_remarks() {

		$(".inputForm").prop("disabled", false);
		$("#edit-remarks").hide();
		$("#update-remarks").show();
	}

	function userAction(userNo, request) {

		var messej = "";

		if (request == 'resetPass') {
			messej = 'Are you sure to reset password for this User Account?';
		} else if (request == 'activateAccount') {
			messej = 'Are you sure to activate this User Account?';
		} else if (request == 'deactivateAccount') {
			messej = 'Are you sure to deactivate this User Account?';
		} else if (request == 'deleteAccount') {
			messej = 'Are you really sure to delete this User Account?';
		} else {

			messej = "";
		}

		$.alert.open({
			type: 'warning',
			cancel: false,
			content: messej,
			buttons: {
				OK: 'Yes',
				NO: 'Not now'
			},

			callback: function(button) {
				if (button == 'OK') {

					$.ajax({
						type: "POST",
						url: "functionquery.php?request=" + request,
						data: {
							userNo: userNo
						},
						success: function(data) {


							$.alert.open({
								type: 'warning',
								title: 'Info',
								icon: 'confirm',
								cancel: false,
								content: data,
								buttons: {
									OK: 'Yes'
								},

								callback: function(button) {
									if (button == 'OK') {

										$("#addUserAccount").modal("hide");
										getdefault('useraccount')
									}

								}
							});
						}
					});
				}

			}
		});
	}

	function addUserAccount() {

		$("#addUserAccount").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#addUserAccount").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=addUserAccount",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".addUserAccount").html(data);
			}
		});
	}

	function defaultPassword() {

		var password = "Hrms2014";
		$("[name= 'password']").val(password);
		$("[name= 'password']").prop("disabled", true);
		$("[name= 'password']").css("border-color", "#ccc");
	}

	function submitUserAccount() {

		var empId = $("[name='empId']").val();
		var usertype = $("[name = 'usertype']").val();
		var username = $("[name = 'username']").val();
		var password = $("[name = 'password']").val();

		if (password == "") {

			$.alert.open({
				type: 'warning',
				cancel: false,
				content: "Please Fill-up Required Fields!",
				buttons: {
					OK: 'Ok'
				},

				callback: function(button) {
					if (button == 'OK') {

						if (password == "") {

							$("[name = 'password']").css("border-color", "#dd4b39");
						}
					}

				}
			});
		} else {

			$.ajax({
				type: "POST",
				url: "functionquery.php?request=submitPromoAccount",
				data: {
					empId: empId,
					usertype: usertype,
					username: username,
					password: password
				},
				success: function(data) {

					data = data.trim();
					if (data == "Ok") {

						$.alert.open({
							type: 'warning',
							title: 'Info',
							icon: 'confirm',
							cancel: false,
							content: "Promo Account Successfully Saved",
							buttons: {
								OK: 'Yes'
							},

							callback: function(button) {
								if (button == 'OK') {

									$("#addUserAccount").modal("hide");
									getdefault('useraccount');
								}

							}
						});

					} else if (data == "Exist") {

						errDup("Username Already Exist");
					} else {

						alert(data);
					}
				}
			});
		}
	}

	function view201Files(no, title) {

		$("#view201File").modal({
			backdrop: 'static',
			keyboard: false
		});

		$(".201-title").html(title);
		$("#view201File").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=view201File",
			data: {
				empId: empId,
				no: no,
				page: ""
			},
			success: function(data) {

				$(".view201File").html(data);
			}
		});
	}

	function pagi(no, page) {

		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=view201File",
			data: {
				empId: empId,
				no: no,
				page: page
			},
			success: function(data) {

				$(".view201File").html(data);
			}
		});
	}

	function upload201Files() {

		$("#upload201Files").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#upload201Files").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=upload201Files",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".upload201Files").html(data);
			}
		});
	}

	function validateFile() {

		$("[name = 'file_upload[]']").css("border-color", "#ccc");
		var chk = $("[name = 'file_upload[]']");
		var img = "";

		for (var i = 0; i < chk.length; i++) {

			img = chk[i].value;
			var res = '';
			var i = img.length - 1;
			while (img[i] != ".") {
				res = img[i] + res;
				i--;
			}

			//checks the file format
			if (res != "PNG" && res != "jpg" && res != "JPG" && res != "png") {
				$("[name = 'file_upload[]']").val("");
				errDup('Invalid File Format. Take note on the allowed file!');
				return;
			}
		}
	}

	function viewProfile() {

		var empId = $("[name = 'empId']").val();
		window.open("../administrator/personnel_info_pdf.php?app_id=" + empId);
	}

	function changeProfilePic() {

		$("#profilePic").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#profilePic").modal("show");
		var empId = $("[name = 'empId']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=changeProfilePic",
			data: {
				empId: empId
			},
			success: function(data) {

				$(".profilePic").html(data);
			}
		});
	}

	// newly added module add agency
	function select_agency(agency_code) {

		var promo_company = $("input[name = 'company_name']").val();

		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=company_list",
			data: {
				agency_code: agency_code,
				promo_company: promo_company
			},
			success: function(data) {

				$("select[name = 'company']").html(data);
			}
		});
	}

	function showRL(resignation_img) {
		console.log(resignation_img)
		$("#body_rl").html("<img src='" + resignation_img + "' width='100%' height='100%'>")
	}
</script>