	<style type="text/css">
		.search-results {
			box-shadow: 5px 5px 5px #ccc;
			margin-top: 1px;
			margin-left: 0px;
			background-color: #F1F1F1;
			width: 57%;
			border-radius: 3px 3px 3px 3px;
			font-size: 18x;
			padding: 8px 10px;
			display: block;
			position: absolute;
			z-index: 9999;
			max-height: 300px;
			overflow-y: scroll;
			overflow: auto;
		}

		.rqd {
			color: red;
		}
	</style>

	<body onload="msgOption('div_secureclearance')"></body>

	<section class="content-header">
		<h1>
			CLEARANCE PROCESSING
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"><?php echo $module; ?></a></li>
			<li class="active"><?php echo $subMenu; ?></li>
		</ol>
	</section>

	<section class="content">
		<!-- <div class="container-fluid">
			<div class='row' style='width:100%;margin:auto'>
				<span style='font-size:24px'> &nbsp;  CLEARANCE PROCESSING </span> <br> -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">


					<div class="box-body">

						<div class="row">

							<div class='col-md-2' style="font-size: 12px">
								<div class="list-group">
									<!-- <a href='javascript:void(0)' class="list-group-item" readonly onclick="msgOption('div_notifyres')">
										<i class="glyphicon glyphicon-pencil"></i> &nbsp;Notify Resignation
									</a> -->
									<a href='javascript:void(0)' class="list-group-item" id='secureclearance' onclick="msgOption('div_secureclearance')">
										<b> SECURE </b> Clearance
									</a>
									<a href='javascript:void(0)' class="list-group-item" id='uploadclearance' onclick="msgOption('div_uploadclearance')">
										<b> UPLOAD </b>Clearance and <br>Change Status
									</a>
									<a href='javascript:void(0)' class="list-group-item" id='reprintclearance' onclick="msgOption('div_reprintclearance')">
										<b> REPRINT </b> Clearance
									</a>
									<a href='javascript:void(0)' class="list-group-item" id='listofwhosecure' onclick="msgOption('div_listofwhosecure')">
										<b> LIST </b> of Employees <br>who secured clearance
									</a>
									<a href='javascript:void(0)' class="list-group-item" id='processflow' onclick="msgOption('div_clearanceprocessflow')">
										<b> PROCESS </b> Flow
									</a>
								</div>

								<div>
									LIST LEGEND:
									<br> SECURE = Date Secure
									<br> EFFECTIVE = Date Effectivity
									<br> CUR_STATUS = Current Status
									<br> C_STAT = Clearance Status
									<br> TYPE = Promo Type Status
								</div>
							</div>
							<div class='col-md-10'>
								<input type="hidden" name="emp_id">

								<div style="border:solid 1px #ccc; width:99%;background-color:white;" class="row">
									<div class="col-md-12 show-form">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- 	</div>
		</div> -->
		<br>
	</section>

	<div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"> View Details </h4>
				</div>
				<div class="modal-body" id='clearanceDetails'>
				</div>
				<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_rl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"> </h4>
				</div>
				<div class="modal-body" id='body_rl'>

				</div>
				<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
			</div>
		</div>
	</div>




	<link rel="stylesheet" href="../css/sweetalert.css" type="text/css" media="screen, projection" />
	<script src="../jquery/sweetalert.js"></script>

	<!-- <link rel="stylesheet" type="text/css" media="all" href="../css/jquery-ui.css" />
 <script type="text/javascript" src="../jquery/jquery-latest.min.js"></script>
<script type="text/javascript" src="../jquery/jquery-ui.js"></script>  -->

	<script>
		/*$(document).ready(function() {
		$("#submit_printclearance_btn").attr("disabled", "disabled");	
	});*/
		var dateeffective;

		function namesearch(key) {
			$(".search-results").show();

			var str = key.trim();
			$(".search-results").hide();
			if (str == '') {
				$(".search-results-loading").slideUp(100);
			} else {
				$.ajax({
					type: "POST",
					url: "functionquery_clearance.php?request=findEmployeeforClearance",
					data: {
						str: str
					},
					success: function(data) {
						data = data.trim();
						if (data != "") {
							$(".search-results").show().html(data);
						} else {
							$(".search-results").hide();
						}
					}
				});
			}
		}

		function getEmpId(id) {
			var id = id.split("*");
			var empId = id[0].trim();
			var name = id[1].trim();
			var type = id[2].trim();


			$("[name=promotype]").val(type);

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_promo_store",
				data: {
					empId: empId,
					type: type
				},
				success: function(data) {
					$("#formstore").html(data);
					$('#reason').prop('disabled', false)
				}
			});

			//check existing if nag secure ug clearance
			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=check_employee_secure_clearance",
				data: {
					empId: empId
				},
				success: function(data) {
					if (data.trim() == "error") {
						swal("Oppss", "Employee has already secured Clearance!", "error");
						$("[name='empid']").val("");
					}
				}
			});

			if (reason == "Termination") {
				//check eoc date and emptype // contractual employees only
				$.ajax({
					type: "POST",
					url: "functionquery_clearance.php?request=getEOCdate",
					data: {
						empId: empId
					},
					success: function(data) {
						if (data.trim() == "error") {
							swal("Oppss", "Please check the employee type!", "error");
							$("[name='empid']").val("");
						} else {
							data1 = data.split("+");
							$("[name='date_resignation']").val(data1[1]);
						}
					}
				});
			}

			$("[name='empid']").val(empId + " * " + name);
			$(".search-results").hide();
		}

		function msgOption(code) {

			$(".show-form").html('<div style="text-align: center;"><img src="images/circle.gif"></div>');

			if (code == "div_secureclearance") {
				$("#secureclearance").addClass("list-group-item active");
				$("#uploadclearance").removeClass("active");
				$("#processflow").removeClass("active");
				$("#listofwhosecure").removeClass("active");
				$("#reprintclearance").removeClass("active");
				$("#taggingstatus").removeClass("active");

			} else if (code == "div_uploadclearance") {

				$("#submit_printclearance_btn").attr("disabled", "disabled");

				$("#uploadclearance").addClass("list-group-item active");
				$("#secureclearance").removeClass("active");
				$("#processflow").removeClass("active");
				$("#listofwhosecure").removeClass("active");
				$("#reprintclearance").removeClass("active");
				$("#taggingstatus").removeClass("active");

			} else if (code == 'div_clearanceprocessflow') {

				$("#processflow").addClass("list-group-item active");
				$("#secureclearance").removeClass("active");
				$("#uploadclearance").removeClass("active");
				$("#listofwhosecure").removeClass("active");
				$("#reprintclearance").removeClass("active");
				$("#taggingstatus").removeClass("active");
			} else if (code == 'div_listofwhosecure') {

				$("#listofwhosecure").addClass("list-group-item active");
				$("#processflow").removeClass("active");
				$("#secureclearance").removeClass("active");
				$("#uploadclearance").removeClass("active");
				$("#reprintclearance").removeClass("active");
				$("#taggingstatus").removeClass("active");
			} else if (code == 'div_reprintclearance') {

				$("#reprintclearance").addClass("list-group-item active");
				$("#processflow").removeClass("active");
				$("#secureclearance").removeClass("active");
				$("#uploadclearance").removeClass("active");
				$("#listofwhosecure").removeClass("active");
				$("#taggingstatus").removeClass("active");

			} else if (code == "div_taggingstatus") {

				$("#taggingstatus").addClass("list-group-item active");
				$("#processflow").removeClass("active");
				$("#secureclearance").removeClass("active");
				$("#uploadclearance").removeClass("active");
				$("#listofwhosecure").removeClass("active");
				$("#reprintclearance").removeClass("active");
			}

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=" + code,
				success: function(data) {

					$('.show-form').html(data);

					//if form secure clearance submit / print clearance is click
					$("form#printClearance_form").submit(function(e) {

						e.preventDefault();
						var formData = new FormData(this);

						var empid = $("#empidClearance").val();
						var id = empid.split("*");
						var reason = $("#reason").val();
						var dateres = $("#date_resignation").val();
						var resLetter = "";

						var empId = id[0].trim();
						var name = id[1].trim();

						swal({
								title: "",
								text: "Are you sure to submit now?",
								type: "warning",
								showCancelButton: true,
								confirmButtonClass: "btn-danger",
								confirmButtonText: "Yes!",
								cancelButtonText: "No!",
								closeOnConfirm: false,
								closeOnCancel: true
							},

							function(isConfirm) {
								if (isConfirm) {

									var store = $("[name='store']").val();
									var promotype = $("[name='promotype']").val();
									//alert("store: "+ store +" promotype: "+ promotype)	

									$.ajax({
										url: "functionquery_clearance.php?request=insert_secure_clearance",
										type: 'POST',
										data: formData,
										enctype: 'multipart/form-data',
										async: true,
										cache: false,
										contentType: false,
										processData: false,
										success: function(data) {
											var data = data.split("+");
											if (data[0].trim() == "success") {
												swal({
														title: "Clearance Processing is Successfull!",
														text: "Do you want to Print Clearance Now or Later?",
														type: "success",
														showCancelButton: true,
														confirmButtonClass: "btn-danger",
														confirmButtonText: "PRINT NOW!",
														cancelButtonText: "LATER!",
														closeOnConfirm: true,
														closeOnCancel: true
													},

													function(isConfirm) {
														if (isConfirm) {

															if (reason == "Deceased") {
																alert("Generating Clearance...");
																window.open('../report/deceased_clearance.php?empid=' + empId, 'new');
															} else {
																if (data[0].trim() == "success") {
																	$("#empidClearance").val("");
																	$("#reason").val("");
																	$(".label_date").val("");
																	$("#date_resignation").hide();

																	alert("Generating Clearance...");
																	msgOption('div_secureclearance');
																	window.open('../report/promo_clearance.php?empid=' + empId + "&scprdetailsid=" + data[1], 'new');

																}
															}
														} else {
															msgOption('div_secureclearance');
														}
													});
											} else {
												swal("Error", "Cannot secure clearance, either employee has existing clearance record that is still pending.", "error");
												$("#printClearance_form").trigger('reset');
											}
										}
									});
								}
							});
					});

					//UPLOAD SIGNED CLEARANCE
					$("form#uploadSignedClearance").submit(function(e) {

						e.preventDefault();
						var formData = new FormData(this);

						var empid = $("#empid").val();
						var id = empid.split("*");
						var empId = id[0].trim();

						var remarks = $("#remarks").val();
						if (remarks != '') {
							swal({
									title: "Confirm Signed Clearance Submission",
									text: "Are you sure to submit now?",
									type: "warning",
									showCancelButton: true,
									confirmButtonClass: "btn-danger",
									confirmButtonText: "YES!",
									cancelButtonText: "NO!",
									closeOnConfirm: false,
									closeOnCancel: true
								},

								function(isConfirm) {
									if (isConfirm) {

										$.ajax({
											url: "functionquery_clearance.php?request=upload_signed_clearance",
											type: 'POST',
											data: formData,
											enctype: 'multipart/form-data',
											async: true,
											cache: false,
											contentType: false,
											processData: false,
											success: function(data) {

												var data = data.split("+");
												swal(data[0], data[1], data[2]);

												if (data[0] != "error") {
													setTimeout(function() {
														window.location = window.location.href;
													}, 2000);
												}
												setTimeout(function() {
													location.reload();
												}, 2000);
											},
										});
									}
								});
						}
					});
				}
			});
		}

		function show_RL(imgSrc, empid) {
			var name = "";
			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=getNames",
				data: {
					empid: empid
				},
				success: function(data) {

					$("#myModalLabel").html("Document uploaded by: " + data);
					$("#body_rl").html("<img src='" + imgSrc + "' width='100%' height='90%'>");
				}
			});
		}

		function show_Clearance() {
			$("#myModalLabel").html("View Details");
		}


		function namesearch_2(key) {

			$(".search-results").show();

			var str = key.trim();
			$(".search-results").hide();
			if (str == '') {
				$(".search-results-loading").slideUp(100);
			} else {
				$.ajax({
					type: "POST",
					url: "functionquery_clearance.php?request=findEmployeeforUploadSignedClearance",
					data: {
						str: str
					},
					success: function(data) {
						data = data.trim();
						if (data != "") {
							$(".search-results").show().html(data);
						} else {

							$(".search-results").hide();
						}
					}
				});
			}
		}

		function getEmpId_2(id) {

			var id = id.split("*");
			var empId = id[0].trim();
			var name = id[1].trim();
			var status = id[2].trim();
			var type = id[3].trim();

			//set promo type
			$("[name=promotype]").val(type);
			//set store 
			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_promo_store_upload",
				data: {
					empId: empId,
					type: type
				},
				success: function(data) {
					$("#formstore").html(data);
				}
			});

			if (status == 'Deceased') {

			} else {

			}

			$("#submit_printclearance_btn").attr("disabled", "disabled");
			$("[name='empid']").val(empId + " * " + name);
			$(".search-results").hide();
		}

		function get_epas() {
			var store = $("[name='store']").val();
			var empid = $("[name='empid']").val();
			empid = empid.split('*');
			empid = empid[0].trim();

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=getEPAS",
				data: {
					empid: empid,
					store: store
				},
				success: function(data) {

					if (data == 0) {
						swal("Oppss", "Employee must secure EPAS first!", "error");

						$("#remarks").attr("disabled", "disabled");
						$("#status").attr("disabled", "disabled");
						$("#clearance").attr("disabled", "disabled");
						$("#submit_printclearance_btn").attr("disabled", "disabled");
						$("#epas").val("");
						$("#epas").attr("disabled", "disabled");
					} else if (data == "Seasonal") {
						$("#remarks").removeAttr("disabled");
						$("#remarks").val("No EPAS for Seasonal with 15days below contract");
						$("#status").removeAttr("disabled");
						$("#clearance").removeAttr("disabled");
						$("#submit_printclearance_btn").removeAttr("disabled");
						$("#epas").removeAttr("disabled");
					} else {
						$("#remarks").removeAttr("disabled");
						$("#status").removeAttr("disabled");
						$("#clearance").removeAttr("disabled");
						$("#submit_printclearance_btn").removeAttr("disabled");
						$("#epas").removeAttr("disabled");
						$("#showEpas").html(data);
					}
				}
			});

		}

		//search for employees who secure clearance for reprint
		function namesearch_reprint(key) {
			$(".search-results-reprint").show();

			var str = key.trim();
			$(".search-results").hide();
			if (str == '') {
				$(".search-results-loading").slideUp(100);
			} else {
				$.ajax({
					type: "POST",
					url: "functionquery_clearance.php?request=findEmployeeforClearanceReprint",
					data: {
						str: str
					},
					success: function(data) {

						console.log(data);
						data = data.trim();
						if (data != "") {
							$(".search-results").show().html(data);
						} else {

							$(".search-results").hide();
						}
					}
				});
			}
		}

		function getEmpId_reprint(id) {
			var id = id.split("*");
			var empId = id[0].trim();
			var name = id[1].trim();
			var scid = id[2].trim();
			var type = id[3].trim();
			//var clearance= id[3].trim();

			$("[name='empid']").val(empId + " * " + name);
			$("input[name = 'emp_id']").val(empId);
			$(".search-results").hide();

			$("#scid").val(scid);
			$("[name=promotype]").val(type);

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_promo_store_reprint",
				data: {
					empId: empId,
					type: type
				},
				success: function(data) {
					$("#formstore").html(data);
				}
			});
		}

		function show_clearance_reprint() {
			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=record_clearance_reprint",
				data: {
					str: str
				},
				success: function(data) {
					data = data.trim();
					if (data != "") {
						$(".search-results").show().html(data);
					} else {

						$(".search-results").hide();
					}
				}
			});
		}

		function get_date_effectivity() {
			var promotype = $("[name='promotype']").val();
			var empid = $("[name='empid']").val();
			empid = empid.split('*');
			empid = empid[0].trim();

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_date_effectivity",
				data: {
					promotype: promotype,
					empid: empid
				},
				success: function(data) {
					data = data.split('*');
					reason = data[0];
					dateeffective = data[1];

					$("[name='reason']").val(reason);
				}
			});
		}



		function getRL(reason) {
			get_date_effectivity();
			$("#date_resignation").show();

			var lbl = '';
			var lbl2 = '';
			if (reason == "Deceased") {
				$(".deceased_form").html(
					'<div class="form-group">' +
					'<label> <span class="rqd"> * </span> Date of Death </label>' +
					'<input type="text" required class="form-control" name="dateofdeath" id="dateofdeath" placeholder="mm/dd/yyyy">' +
					'</div>' +
					'<div class="form-group">' +
					'<label> <span class="rqd"> * </span> Name of Claimant </label>' +
					'<input type="text" required class="form-control" name="claimant" id="claimant">' +
					'</div>' +
					'<div class="form-group">' +
					'<label> <span class="rqd"> * </span>  Relation to the deceased employee </label>' +
					'<select class="form-control" name="relation" id="relation">' +
					'<option> - Choose Relationship - </option>' +
					'<option value="Father"> Father </option>' +
					'<option value="Mother"> Mother </option>' +
					'<option value="Spouse"> Spouse </option>' +
					'<option value="Son"> Son </option>' +
					'<option value="Daughter"> Daughter</option>' +
					'<option value="Sister/Brother"> Sister/Brother</option>' +
					'</select>' +
					'</div>' +
					'<div class="form-group">' +
					'<label> <span class="rqd"> * </span> Cause of Death </label>' +
					'<input type="text" required class="form-control" name="causeofdeath" id="causeofdeath">' +
					'</div>' +
					'<div class="rl_form">' +
					'<div class="form-group">' +
					'<label> <span class="rqd"> * </span> Required Document (Scanned Death Certificate) </label>' +
					'<input type="file" required accept="image/*" name="resignationletter" id="resignationletter" class="btn btn-default"  size="50" >' +
					'</div>' +
					'</div>' +
					'<div class="rl_form">' +
					'<div class="form-group" id="autholetter">' +
					'<label> Required Document (Scanned Authorization Letter) </label>' +
					'<input type="file" accept="image/*" name="authorizationletter" id="authorizationletter" class="btn btn-default"  size="50" >' +
					'</div>' +
					'</div>'

				);
				$(".non_deceased_form").html('');
			} else {
				if (reason == "V-Resigned" || reason == "Ad-Resigned") {
					lbl = "<span class='rqd'> * </span>  Date of Resignation ";
					lbl2 = "<span class='rqd'> * </span>  Required Document (Scanned Resignation Letter) ";
				} else if (reason == "Termination") {

					lbl = "<span class='rqd'> * </span> EOC Date ";
					lbl2 = "<span class='rqd'> * </span>  Required Document (Scanned Notice of End of Contract) ";
					$("#resignationletter").removeAttr("required");
					$("#resignationletter").hide();
					$("#rl_form").hide();
				}


				$(".deceased_form").html('');
				$(".non_deceased_form").html(
					'<div class="form-group">' +
					'<label class="label_date">' + lbl + '</label>' +
					'<input type="text" required class="form-control"  name="date_resignation" id="date_resignation" autocomplete="off" placeholder="mm/dd/yyyy" >' +
					'</div>' +

					'<div class="rl_form" id="rl_form">' +
					'<div class="form-group">' +
					'<label> ' + lbl2 + ' </label>' +
					'<input type="file" required accept="image/*"   name="resignationletter" id="resignationletter" class="btn btn-default"  size="50" >' +
					'</div>' +
					'</div>'
				);

				if (reason == "Termination") {

					$("#resignationletter").removeAttr("required");
					$("#resignationletter").hide();
					$("#rl_form").hide();

				}

			}

			$("#date_resignation").datepicker({
				dateFormat: "mm/dd/yy",
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true
			});
			$("#dateofdeath").datepicker({
				dateFormat: "mm/dd/yy",
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true
			});

			setTimeout(function() {
				if (reason == "Termination") {
					$("[name='date_resignation']").val(dateeffective);
				}
			}, 500);

		}

		function print_clearance(reason, empId, scdetails_id) {
			if (reason == "Deceased") {
				alert("Generating Clearance...");
				msgOption('div_secureclearance');
				window.open('../report/deceased_clearance.php?empid=' + empId, 'new');
			} else {
				alert("Generating Clearance...");
				msgOption('div_secureclearance');
				window.open('../report/promo_clearance.php?empid=' + empId + "&scprdetailsid=" + scdetails_id, 'new');
			}
		}

		function reprint_clearance() {
			var scid = $("#scid").val();
			var reason = $("#reasonreprint").val();

			if (scid != "" && reason != "") {
				$.ajax({
					type: "POST",
					url: "functionquery_clearance.php?request=record_clearance_reprint",
					data: {
						scid: scid,
						reason: reason
					},
					success: function(data) {

						if (data.trim() == "ok") {
							swal("", "You can now view the Clearance!", "success");

							$("#submit_reprintclearance_btn").attr("disabled", true);
							$("#view_reprintclearance_btn").attr("disabled", false);
						}
					}
				});
			} else {
				swal("Oppss", "Reprint Not Allowed!", "error");
			}
		}

		get_reason = function() {
			var empid = $("input[name = 'emp_id']").val();
			var store = $("[name='store']").val();
			var reason = "";
			var scdetails_id = "";

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_store_reprint",
				data: {
					empid: empid,
					store: store
				},
				success: function(data) {
					var data = data.split("*");
					reason = data[0].trim();
					scdetails_id = data[1].trim();
					print_clearance(reason, empid, scdetails_id)
				}
			});
		}

		// filter_year = function(year) {
		// 	$("#_loading").html('<i>Loading, Please wait.....</i>');

		// 	$("#listofwhosecure").addClass("list-group-item active");
		// 	$("#processflow").removeClass("active");
		// 	$("#secureclearance").removeClass("active");
		// 	$("#uploadclearance").removeClass("active");
		// 	$("#reprintclearance").removeClass("active");
		// 	$("#taggingstatus").removeClass("active");

		// 	$.ajax({
		// 		type: "POST",
		// 		url: "functionquery.php?request=div_listofwhosecure",
		// 		data: {
		// 			year: year
		// 		},
		// 		success: function(data) {

		// 			$('.show-form').html(data);
		// 			$("#_loading").html('');
		// 		},
		// 	});

		// }

		showDetails = function(empid, scprid) {
			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_clearance_details",
				data: {
					empid: empid,
					scprid: scprid
				},
				success: function(data) {
					$("#clearanceDetails").html(data);
				}
			});
		}

		function filter_year(year) {
			console.log(year)
			$(".show-form").html('<div style="text-align: center;"><img src="images/circle.gif"></div>');
			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=div_listofwhosecure",
				data: {
					year: year
				},
				success: function(data) {

					$('.show-form').html(data);
				}
			});
		}
	</script>