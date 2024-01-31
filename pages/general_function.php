<script>
    $(function() {

        let server = $("select#filter_server").val();

        var example = $('#example').DataTable({
            destroy: true,
            "ajax": {
                url: "functionquery.php?request=loadPromoIupdate",
                type: "post",
                data: {
                    server: server
                },
            },
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    targets: [1],
                    visible: false,
                    searchable: false
                }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [
                [2, 'asc']
            ]
        });

        example.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                example.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                example.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function() {
            ("Some selection or deselection going on")
            if (example.rows({
                    selected: true
                }).count() !== example.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });

        $("button.update_server").click(function() {

            var value = example.rows('.selected').data();
            var server = $("select#filter_server").val();

            var newCHK = []
            $.each(value, function(i) {
                var id = value[i][1]
                newCHK.push(id)
            })

            if (newCHK.length === 0) {
                return
            }

            $("button.update_server").prop("disabled", true);
            $("span.display_msg").html("<img src='images/icon/loading.gif'> <span>Please Wait...</span>");
            $.ajax({
                type: "POST",
                url: "functionquery.php?request=update_server",
                data: {
                    newCHK: newCHK,
                    server: server
                },
                success: function(data) {

                    if (data.trim() == 'success') {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: "Promo records update successfully!",
                            buttons: {
                                OK: 'Ok'
                            },

                            callback: function(button) {
                                if (button == 'OK') {
                                    $("button.update_server").prop("disabled", false);
                                    $("span.display_msg").html("Update Records");
                                    window.location.reload();
                                }
                            }
                        });
                    } else {
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Unable to connect to the server!",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("button.update_server").prop("disabled", false);
                                    $("span.display_msg").html("Update Records");
                                }

                            }
                        });
                    }
                }
            });
        });

        $(".table1").DataTable();

        $('.table2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });

        $(".table3").DataTable({
            "ordering": false
        });

        $(".select2").select2();
        $("span.select2").css("width", "100%");
        //   $("span.select2").css("display", "none");

        var dashboard = $("[name = 'dashboard']").val();
        if (dashboard == "comeOut") {

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas);
            var PieData = [

                <?php

                $c = 0;
                $sqlBU = mysql_query("SELECT bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active'") or die(mysql_error());
                while ($rowBU = mysql_fetch_array($sqlBU)) {

                    $sqlPromo = mysql_query("SELECT COUNT(promo_id) AS num FROM promo_record WHERE " . $rowBU['bunit_field'] . " = 'T'") or die(mysql_error());
                    $rowPromo = mysql_fetch_array($sqlPromo); ?>

                    {
                        value: <?php echo $rowPromo['num']; ?>,
                        color: "<?php echo $pieColor[$c]; ?>",
                        highlight: "<?php echo $pieColor[$c]; ?>",
                        label: "<?php echo $rowBU['bunit_name']; ?>"
                    },
                <?php

                    $c++;
                }
                ?>
            ];
            var pieOptions = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 0, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 100,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
            };
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Doughnut(PieData, pieOptions);

            // dashboard

            setTimeout(function() {

                $.ajax({
                    url: "functionquery.php?request=newPromo",
                    success: function(data) {

                        $("#newPromo").html(data);
                    }
                });

                $.ajax({
                    url: "functionquery.php?request=birthdayToday",
                    success: function(data) {

                        $("#birthdayToday").html(data);
                    }
                });

                $.ajax({
                    url: "functionquery.php?request=activePromo",
                    success: function(data) {

                        $("#activePromo").html(data);
                    }
                });

                $.ajax({
                    url: "functionquery.php?request=eocToday",
                    success: function(data) {

                        $("#eocToday").html(data);
                    }
                });

                $.ajax({
                    url: "functionquery.php?request=failedEpas",
                    success: function(data) {

                        $("#failedEpas").html(data);
                    }
                });

            }, 1000);
        }

        $("[name='searchPromo']").keypress(function(evt) {
            var val = this.value;
            if (evt.which == 13) {
                window.location = "?p=search&&module=Promo&&search=" + val;
            }
        });

        $("[name='firstnameApp']").keypress(function(evt) {

            var val = this.value;
            var lastname = $("[name='lastnameApp']").val();

            if (evt.which == 13) {
                searchApp(lastname, val);
            }
        });

        $("[name='lastnameApp']").keypress(function(evt) {

            var val = this.value;
            var firstname = $("[name='firstnameApp']").val();

            if (evt.which == 13) {
                searchApp(val, firstname);
            }
        });

        if ("<?php echo @$_GET['p']; ?>" == "profile") {

            getdefault('basicinfo');
        }

        $("form#dataUsername").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var newUsername = $("[name = 'newUsername']").val();
            var confirmUsername = $("[name = 'confirmUsername']").val();

            if (newUsername != confirmUsername) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Username Mismatch! <br>Please check that you've entered and confirmed your username!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            $("[name = 'confirmUsername']").val("");
                            $("[name = 'confirmUsername']").focus();
                        }

                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=changeUsername",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "You have Successfully change your username!<br> The system will logout and kindly log in again. Thank You!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        window.location = "../logout.php?com=logout";
                                    }

                                }
                            });

                        } else {

                            $.alert.open({
                                type: 'warning',
                                cancel: false,
                                content: response,
                                buttons: {
                                    OK: 'Ok'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("[name = 'newUsername']").val("");
                                        $("[name = 'confirmUsername']").val("");
                                        $("[name = 'newUsername']").focus();
                                    }

                                }
                            });
                        }

                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        });

        $("form#dataPassword").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var newPassword = $("[name = 'newPassword']").val();
            var confirmPassword = $("[name = 'confirmPassword']").val();

            if (newPassword != confirmPassword) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Password Mismatch! <br>Please check that you've entered and confirmed your password!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            $("[name = 'confirmPassword']").val("");
                            $("[name = 'confirmPassword']").focus();
                        }

                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=changePassword",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "You have Successfully change your password!<br> The system will logout and kindly log in again. Thank You!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        window.location = "../logout.php?com=logout";
                                    }

                                }
                            });

                        } else {

                            $.alert.open({
                                type: 'warning',
                                cancel: false,
                                content: "Please input the exact old password!!",
                                buttons: {
                                    OK: 'Ok'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("[name = 'currentPassword']").val("");
                                        $("[name = 'currentPassword']").focus();
                                    }

                                }
                            });
                        }

                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        });

        $("form#dataProcessRenewal").submit(function(e) {
            e.preventDefault();

            $("button.submit-contract").prop("disabled", true);
            var formData = new FormData(this);
            var edited = $("[name = 'edited']").val();
            var companyDuration = $("[name = 'companyDuration']").val();
            var startdate = $("[name = 'startdate']").val();
            var eocdate = $("[name = 'eocdate']").val();
            var duration = $("[name = 'duration']").val();
            var introName = "";
            var introValue = "";
            var introMsg = "";
            var counter2 = $("[name = 'counter2']").val();

            for (var x = 1; x <= counter2; x++) {

                var intro = $("#intro_" + x).attr('name');
                var introVal = $("[name = '" + intro + "']").val();

                introName += intro + "||";
                introValue += introVal + "||";

                if (introVal == "") {

                    introMsg = "true";
                }
            }

            var agency = "";
            var company = "";
            var promoType = "";
            var department = "";
            var vendor = "";
            var product = "";
            var position = "";
            var positionlevel = "";
            var empType = "";
            var contractType = "";
            var statCut = "";

            if (edited == "true") {

                agency = $("[name = 'agency_select']").val();
                company = $("[name = 'company_select']").val();
                promoType = $("[name = 'promoType_select']").val();
                department = $("[name = 'department_select']").val();
                vendor = $("[name = 'vendor_select']").val();
                product = $("[name = 'product_select']").val();
                position = $("[name = 'position_select']").val();
                positionlevel = $("[name = 'positionlevel_select']").val();
                empType = $("[name = 'empType_select']").val();
                contractType = $("[name = 'contractType_select']").val();
                statCut = $("select[name = 'cutoff_select']").val();

                var store = "";
                var chkNum = 0;
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
                            store += bunit_id + "|";
                        }
                    }
                }

            } else {

                department = $("[name = 'department']").val();
                contractType = $("[name = 'contractType']").val();
            }

            if ((edited == "true" &&
                    (company == "" ||
                        promoType == "" ||
                        department == "" ||
                        position == "" ||
                        positionlevel == "" ||
                        empType == "" ||
                        contractType == "" ||
                        store == "" ||
                        (promoType == "ROVING" && chkNum < 2) ||
                        (companyDuration == "" && (contractType == "Seasonal" || department == "HOME AND FASHION" || department == "FIXRITE" || department == "EASY FIX")) ||
                        startdate == "" ||
                        eocdate == "" ||
                        duration == "" ||
                        introMsg == "true" ||
                        statCut == ""))) {

                $("button.submit-contract").prop("disabled", false);

                if (edited == "true" && store == "") {

                    errDup("Please Select Business Unit!");
                } else if (edited == "true" && (promoType == "ROVING" && chkNum < 2)) {

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

                                if (edited == "true" && company == "") {

                                    $("[name = 'company_select']").css("border-color", "#dd4b39");
                                }

                                if (edited == "true" && position == "") {

                                    $("[name = 'position_select']").css("border-color", "#dd4b39");
                                }

                                if ((companyDuration == "" && (contractType == "Seasonal" || department == "HOME AND FASHION" || department == "FIXRITE" || department == "EASY FIX"))) {

                                    $("[name = 'companyDuration']").css("border-color", "#dd4b39");
                                }

                                if (startdate == "") {

                                    $("[name = 'startdate']").css("border-color", "#dd4b39");
                                }

                                if (eocdate == "") {

                                    $("[name = 'eocdate']").css("border-color", "#dd4b39");
                                }

                                if (duration == "") {

                                    $("[name = 'duration']").css("border-color", "#dd4b39");
                                }

                                if (statCut == "") {

                                    $("select[name = 'cutoff_select']").css("border-color", "#dd4b39");
                                }

                                if (introMsg == "true") {

                                    for (var x = 1; x <= counter2; x++) {

                                        var intro = $("#intro_" + x).attr('name');
                                        var introVal = $("[name = '" + intro + "']").val();

                                        introName += intro + "|";
                                        introValue += introVal + "|";

                                        if (introVal == "") {

                                            $("[name = '" + intro + "']").css("border-color", "#dd4b39");

                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } else {

                $.ajax({
                    url: "functionquery.php?request=processRenewal",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        response = data.split("||");
                        if (response[0].trim() == "success") {

                            $("button.submit-contract").prop("disabled", false);
                            $("#printContractAndPermit").modal({
                                backdrop: 'static',
                                keyboard: false
                            });

                            $("#printContractAndPermit").modal("show");
                            var empId = $("[name = 'empId']").val();

                            $.ajax({
                                type: "POST",
                                url: "functionquery.php?request=printContractAndPermit",
                                data: {
                                    empId: empId
                                },
                                success: function(data) {

                                    $(".printContractAndPermit").html(data);
                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        });

        $("form#dataSeminar").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var empId = $("[name = 'appId']").val();
            var semName = $("[name = 'semName']").val();
            var semDate = $("[name = 'semDate']").val();
            var semLocation = $("[name = 'semLocation']").val();
            var semCertificate = $("[name = 'semCertificate']").val();

            if (semName == "" || semLocation == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (semName == "") {

                                $("[name = 'semName']").css("border-color", "#dd4b39");
                            }

                            if (semLocation == "") {

                                $("[name = 'semLocation']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });

            } else {

                $.ajax({
                    url: "employee_information_details.php?request=submitSeminar",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.split("||");

                        if (response[0].trim() == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Seminar/Eligibility/Training Information Successfully " + response[1].trim(),
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("#addSeminar").modal("hide");
                                        getdefault('seminar');
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        });

        // upload resignation letter
        $("form#dataUploadResignationLetter").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var resignationLetter = $("[name = 'resignationLetter']").val();

            if (resignationLetter == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (resignationLetter == "") {

                                $("input[name = 'resignationLetter']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });

            } else {

                $.ajax({
                    url: "functionquery.php?request=submitResignationLetter",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Resignation Letter Successfully Saved!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("#upload_resignationLetter").modal("hide");
                                        window.location.reload();
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        });

        $("form#dataEmploymentHistory").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var company = $("[name = 'company']").val();
            var address = $("[name = 'address']").val();
            var position = $("[name = 'position']").val();
            var startdate = $("[name = 'startdate']").val();
            var eocdate = $("[name = 'eocdate']").val();

            if (company == "" || position == "") {

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

                            if (position == "") {

                                $("[name = 'position']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });

            } else {

                $.ajax({
                    url: "employee_information_details.php?request=submitEmploymentHist",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.split("||");

                        if (response[0].trim() == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Employment History Successfully " + response[1].trim(),
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("#addEmploymentHist").modal("hide");
                                        getdefault('history');
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        });

        $("form#dataUploadPromoScannedFile").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "employee_information_details.php?request=uploadPromoScannedFile",
                type: 'POST',
                data: formData,
                success: function(data) {
                    response = data.split("||");

                    if (response[0].trim() == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: response[1].trim(),
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#uploadPromoScannedFile").modal("hide");
                                    getdefault('employment');
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                },
                async: false,
                cache: false,
                contentType: false,
                processData: false

            });
        });

        $("form#dataProfilePic").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "employee_information_details.php?request=uploadProfilePic",
                type: 'POST',
                data: formData,
                success: function(data) {
                    response = data.trim();

                    if (response == "success") {

                        var loc = document.location;
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Photo Successfully Updated!",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#profilePic").modal("hide");
                                    window.location = loc;
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                },
                async: false,
                cache: false,
                contentType: false,
                processData: false

            });
        });

        $("form#dataUploadScannedFile").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "employee_information_details.php?request=uploadScannedFile",
                type: 'POST',
                data: formData,
                success: function(data) {
                    response = data.split("||");

                    if (response[0].trim() == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: response[1].trim(),
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#uploadScannedFile").modal("hide");
                                    getdefault('employment');
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                },
                async: false,
                cache: false,
                contentType: false,
                processData: false

            });
        });

        $("form#data201File").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var sel201File = $("[name = 'sel201File']").val();
            var file = $("[name = 'file_upload[]']");

            var chkNum = 0;
            for (var i = 0; i < file.length; i++) {

                if (file[i].value != "") {

                    chkNum++;
                }
            }

            if (sel201File == "" || chkNum == 0) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (sel201File == "") {

                                $("[name = 'sel201File']").css("border-color", "#dd4b39");
                            }

                            if (chkNum == 0) {

                                $("[name = 'file_upload[]']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "employee_information_details.php?request=upload201File",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Successfully Uploaded!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("#upload201Files").modal("hide");
                                        getdefault('201doc');
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("form#dataUploadClearanceforRemoveOutlet").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var counter = $("[name = 'counter']").val();
            var empId = $("[name = 'empId']").val();

            var chkNum = 0;
            for (var i = 1; i <= counter; i++) {

                var file = $(".clearance_" + i).val();
                if (file != "") {

                    chkNum++;
                }
            }

            if (chkNum != counter) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Upload Clearance Needed!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            for (var i = 1; i <= counter; i++) {

                                var file = $(".clearance_" + i).val();
                                if (file == "") {

                                    $(".clearance_" + i).css("border-color", "#dd4b39");
                                }
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=savingCleranceforRemoveOutlet",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Successfully Remove the Outlet!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        window.location = "?p=removeOutlet&&module=Outlet";
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("form#dataUploadClearanceforTransferOutlet").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var empId = $("[name = 'empId']").val();
            var recordNo = $("[name = 'recordNo']").val();
            var field = $("[name = 'bunitField[]']");
            var previousStore = $("[name = 'previousStore']").val();
            var counter = $("[name = 'counter']").val();

            var fields = "";
            for (var i = 0; i < field.length; i++) {

                fields += field[i].value + "*";
            }

            var chkNum = 0;
            for (var i = 1; i <= counter; i++) {

                var file = $(".clearance_" + i).val();
                if (file != "") {

                    chkNum++;
                }
            }

            if (chkNum < counter) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Upload Clearance Needed!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            for (var i = 1; i <= counter; i++) {

                                var file = $(".clearance_" + i).val();
                                if (file == "") {

                                    $(".clearance_" + i).css("border-color", "#dd4b39");
                                }
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=savingCleranceforTransferOutlet",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Clearance Successfully Saved!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        window.location = "?p=transferOutletNow&&module=Outlet&&empId=" + empId + "&&recordNo=" + recordNo + "&&field=" + fields + "&&previousStore=" + previousStore;
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("form#dataUploadClearance").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var counter = $("[name = 'counter']").val();
            var empId = $("[name = 'empId']").val();

            var chkNum = 0;
            for (var i = 1; i <= counter; i++) {

                var file = $(".clearance_" + i).val();
                if (file != "") {

                    chkNum++;
                }
            }

            if (chkNum != counter) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Upload Clearance Needed!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            for (var i = 1; i <= counter; i++) {

                                var file = $(".clearance_" + i).val();
                                if (file == "") {

                                    $(".clearance_" + i).css("border-color", "#dd4b39");
                                }
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=savingCleranceforRenewal",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Successfully Uploaded!<br> You can now proceed to renewal process.",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        window.location = "?p=processRenewal&&module=Contract&&empId=" + empId;
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("form#dataUploadClearanceAbenson").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var counter = $("[name = 'counter']").val();

            var chkNum = 0;
            for (var i = 1; i <= counter; i++) {

                var file = $(".clearance_" + i).val();
                if (file != "") {

                    chkNum++;
                }
            }

            if (chkNum != counter) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Upload Clearance Needed!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            for (var i = 1; i <= counter; i++) {

                                var file = $(".clearance_" + i).val();
                                if (file == "") {

                                    $(".clearance_" + i).css("border-color", "#dd4b39");
                                }
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=savingCleranceforRenewal",
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = data.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Scanned Clearance Successfully Uploaded!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("#uploadClearance").modal("hide");
                                        $(".clearance").prop("disabled", true);
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("form#dataRT").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var empId = $("[name = 'employee']").val().split("*");
            var remarks = $("[name = 'remarks']").val();
            var dateEffective = $("[name = 'dateEffective']").val();
            var status = $("[name = 'status']").val();

            if (status != "") {

                var clearanceName = "";
                var clearanceValue = "";
                var clearanceMsg = "";
                var loop = $("[name = 'loop']").val();

                for (var x = 1; x <= loop; x++) {

                    var clearance = $("#clearance_" + x).attr('name');
                    var clearanceVal = $("[name = '" + clearance + "']").val();

                    clearanceName += clearance + "||";
                    clearanceValue += clearanceVal + "||";

                    if (clearanceVal == "") {

                        clearanceMsg = "true";
                    }
                }

                var resignedLetter = "";
                if (status == "Resigned") {

                    resignedLetter = $("[name = 'resignation']").val();
                }

                if (clearanceMsg == "true" || (status == "Resigned" && resignedLetter == "")) {


                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (clearanceMsg == "true") {

                                    var ctrName = clearanceName.split("||");
                                    var ctrVal = clearanceValue.split("||");

                                    for (var i = 0; i < ctrVal.length - 1; i++) {

                                        if (ctrVal[i] == "") {

                                            $("[name = '" + ctrName[i] + "']").css("border-color", "#dd4b39");
                                        }
                                    }
                                }

                                if (status == "Resigned" && resignedLetter == "") {

                                    $("[name = 'resignation']").css("border-color", "#dd4b39");
                                }
                            }
                        }
                    });

                } else {

                    $.ajax({
                        url: "functionquery.php?request=submitPromoRT",
                        type: 'POST',
                        data: formData,
                        success: function(data) {
                            response = data.trim();

                            if (response == "Ok") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Resignation/Termination Successfully Saved",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            window.location = "?p=resignation&&module=Resignation/Termination";
                                        }

                                    }
                                });
                            } else {

                                alert(data);
                            }
                        },
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }

            } else {

                if (empId[0].trim() == "" || remarks == "" || status == "") {

                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (empId[0].trim() == "") {

                                    $("[name = 'employee']").css("border-color", "#dd4b39");
                                }

                                if (remarks == "") {

                                    $("[name = 'remarks']").css("border-color", "#dd4b39");
                                }

                                $("[name = 'status']").css("border-color", "#dd4b39");

                            }

                        }
                    });
                }
            }
        });

        // newly added module (Add Agency)
        $("button.add_agency").click(function() {

            $("#addAgency").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("#addAgency").modal("show");
            $.ajax({
                type: "POST",
                url: "functionquery.php?request=addAgencyForm",
                success: function(data) {

                    $(".addAgency").html(data);
                }
            });
        });

        $("button.submit_agency").click(function() {

            var agency = $("input[name = 'agency_name']").val();
            if (agency.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Agency Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (agency.trim() == "") {

                                $("input[name = 'agency_name']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=addAgency",
                    data: {
                        agency: agency
                    },
                    success: function(data) {

                        var response = data.trim();
                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Agency has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        // $("div#addAgency").modal("hide");
                                        location.reload();
                                    }
                                }
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button.setup_company_btn").click(function() {

            $("div#setup_company").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#setup_company").modal("show");
            $("div.companies").html("");

            $.ajax({
                type: "POST",
                url: "functionquery.php?request=selectAgency",
                success: function(data) {

                    $("div.agency_list").html(data);
                }
            });
        });

        $("button.submit_company").click(function() {

            var agency_code = $("select[name = 'setupan_agency']").val();
            var chk = $("input[name = 'chkCom[]']");
            var newCHK = "";

            for (var i = 0; i < chk.length; i++) {

                if (chk[i].checked == true) {

                    newCHK += chk[i].value + "|_";
                }
            }

            $.post("functionquery.php?request=insert_update_locate_company", {
                agency_code: agency_code,
                newCHK: newCHK
            }, function(data, status) {

                var response = data.trim();
                if (response == "success") {

                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',
                        cancel: false,
                        content: "Company for agency has been setup!",
                        buttons: {
                            OK: 'Yes'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                $("div#setup_company").modal("hide");
                                location.reload();
                            }
                        }
                    });
                } else {

                    console.log(response);
                }
            });
        });

        var dt_company = $("table#dt_company").DataTable({

            "destroy": true,
            "ajax": {
                url: "functionquery.php?request=com_for_agency",
                type: "POST"
            },
            "order": [
                [0, "asc"],
                [1, "asc"]
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt_company').on('click', 'i.delete_company', function() {

            var id = this.id.split("_");
            company_code = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to delete this company?",
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $.post("functionquery.php?request=delete_company", {
                            company_code: company_code
                        }, function(data, status) {

                            var response = data.trim();
                            if (response == "success") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Company has been deleted.",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            var dt_company = $("table#dt_company").DataTable({

                                                "destroy": true,
                                                "ajax": {
                                                    url: "functionquery.php?request=com_for_agency",
                                                    type: "POST"
                                                },
                                                "order": [
                                                    [0, "asc"],
                                                    [1, "asc"]
                                                ],
                                                "columnDefs": [{
                                                    "targets": [2],
                                                    "orderable": false,
                                                    "className": "text-center",
                                                }]
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            });
        });

        var dt_dept = $("table#dt_department").DataTable({

            "destroy": true,
            "ajax": {
                url: "functionquery.php?request=department_list",
                type: "POST"
            },
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [3],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt_department').on('change', 'select.status', function() {

            let id = this.id;
            let [column, dept_id] = this.id.split('-');
            let status = $(this).val();

            let messej = `Are you sure you want to update ${column} to ${status}?`;
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
                            url: "functionquery.php?request=updateDeptStat",
                            data: {
                                column,
                                dept_id,
                                status
                            },
                            success: function(data) {

                                let response = JSON.parse(data);
                                if (response.status == 'success') {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Business Unit ${column} has been updated.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },
                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                } else {
                                    console.log(data);
                                }
                            }
                        });
                    } else {
                        console.log(status)
                        if (status == 'active') {
                            $('select#' + id).val('inactive');
                        } else {
                            $('select#' + id).val('active');
                        }
                    }
                }
            });
        });

        $("form#saveDept").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            for (let entry of formData.entries()) {

                fields.push(entry[0]);
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("select[name='" + field + "']").val();

                if (value === '') {

                    if (!errMessage) {
                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: "Please Fill-up Required Fields!",
                            buttons: {
                                OK: 'Ok'
                            },
                        });
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("select[name='" + field + "']").each(function() {
                        $(this).css("border-color", "#dd4b39");
                    });
                    $("select[name='" + field + "']").on("focus", function() {
                        $(this).css("border-color", "");
                    });
                }
            }
            if (fieldsComplete) {
                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: 'Are you sure you want to Add Department?',
                    buttons: {
                        OK: 'Yes',
                        NO: 'Not now'
                    },
                    callback: function(button) {
                        if (button == 'OK') {
                            $("button.saveDept").prop('disabled', true);
                            $("button.saveDept").html('Please wait...');
                            $.ajax({
                                url: "functionquery.php?request=saveAddDept",
                                type: 'POST',
                                data: formData,
                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.status == 'success') {
                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'confirm',
                                            cancel: false,
                                            content: `Department  has been added.`,
                                            buttons: {
                                                OK: 'OK'
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {
                                                    location.reload();
                                                }
                                            }
                                        });
                                    } else if (response.status == 'exist') {

                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'warning',
                                            cancel: false,
                                            content: 'Data Already exist!',
                                            buttons: {
                                                OK: 'OK'
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {

                                                    $("button.saveDept").prop('disabled', false);
                                                    $("button.saveDept").html('Submit');
                                                }
                                            }
                                        });
                                    } else {
                                        alert(data);
                                    }
                                },
                                async: false,
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                        }
                    }
                })
            }
        });

        var dt_bu = $("table#dt_bu").DataTable({

            "destroy": true,
            "ajax": {
                url: "functionquery.php?request=bu_list",
                type: "POST"
            },
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [4],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt_bu').on('change', 'select.status', function() {

            let id = this.id;
            let [column, bunit_id] = this.id.split('-');
            let status = $(this).val();

            let messej = `Are you sure you want to update ${column} to ${status}?`;
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
                            url: "functionquery.php?request=updateBUstat",
                            data: {
                                column,
                                bunit_id,
                                status
                            },
                            success: function(data) {

                                let response = JSON.parse(data);
                                if (response.status == 'success') {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Business Unit ${column} has been updated.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },
                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                } else {
                                    console.log(data);
                                }
                            }
                        });
                    } else {
                        console.log(status)
                        if (status == 'active') {
                            $('select#' + id).val('inactive');
                        } else {
                            $('select#' + id).val('active');
                        }
                    }
                }
            });
        });

        $("form#saveBU").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            for (let entry of formData.entries()) {

                fields.push(entry[0]);
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val();

                if (value === '') {

                    if (!errMessage) {
                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: "Please Fill-up Required Fields!",
                            buttons: {
                                OK: 'Ok'
                            },
                        });
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").each(function() {
                        $(this).css("border-color", "#dd4b39");
                    });
                    $("input[name='" + field + "'],select[name='" + field + "']").on("focus", function() {
                        $(this).css("border-color", "");
                    });
                }
            }
            if (fieldsComplete) {
                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: 'Are you sure you want to Add Business Unit?',
                    buttons: {
                        OK: 'Yes',
                        NO: 'Not now'
                    },
                    callback: function(button) {
                        if (button == 'OK') {
                            $("button.saveBU").prop('disabled', true);
                            $("button.saveBU").html('Please wait...');
                            $.ajax({
                                url: "functionquery.php?request=saveAddBu",
                                type: 'POST',
                                data: formData,
                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.status == 'success') {
                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'confirm',
                                            cancel: false,
                                            content: `Business Unit  has been added.`,
                                            buttons: {
                                                OK: 'OK'
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {
                                                    location.reload();
                                                }
                                            }
                                        });
                                    } else if (response.status == 'exist') {
                                        var exist = response.field;
                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'warning',
                                            cancel: false,
                                            content: 'Data Already exist!',
                                            buttons: {
                                                OK: 'OK'
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {
                                                    $("input[name='" + exist + "']").each(function() {
                                                        $(this).css("border-color", "#dd4b39");
                                                    });
                                                    $("input[name='" + exist + "']").on("focus", function() {
                                                        $(this).css("border-color", "");
                                                    });
                                                    $("button.saveBU").prop('disabled', false);
                                                    $("button.saveBU").html('Submit');
                                                }
                                            }
                                        });
                                    } else {
                                        alert(data);
                                    }
                                },
                                async: false,
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                        }
                    }
                })
            }
        });

        $("form#saveUpdateBu").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            for (let entry of formData.entries()) {

                fields.push(entry[0]);
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val();

                if (value === '') {

                    if (!errMessage) {
                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: "Please Fill-up Required Fields!",
                            buttons: {
                                OK: 'Ok'
                            },
                        });
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "']").each(function() {
                        $(this).css("border-color", "#dd4b39");
                    });
                    $("input[name='" + field + "']").on("focus", function() {
                        $(this).css("border-color", "");
                    });
                }
            }
            if (fieldsComplete) {
                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: 'Are you sure you want to Update Business Unit?',
                    buttons: {
                        OK: 'Yes',
                        NO: 'Not now'
                    },
                    callback: function(button) {
                        if (button == 'OK') {
                            $.ajax({
                                url: "functionquery.php?request=updateBUdetails",
                                type: 'POST',
                                data: formData,
                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.status == 'success') {
                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'confirm',
                                            cancel: false,
                                            content: `Business Unit  has been updated.`,
                                            buttons: {
                                                OK: 'Yes'
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {

                                                    location.reload();
                                                }
                                            }
                                        });
                                    } else if (response.status == 'exist') {
                                        var exist = response.field;
                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'warning',
                                            cancel: false,
                                            content: 'Data Already exist!',
                                            buttons: {
                                                OK: 'OK'
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {
                                                    $("input[name='" + exist + "']").each(function() {
                                                        $(this).css("border-color", "#dd4b39");
                                                    });
                                                    $("input[name='" + exist + "']").on("focus", function() {
                                                        $(this).css("border-color", "");
                                                    });
                                                }
                                            }
                                        });
                                    } else {
                                        alert(data);
                                    }
                                },
                                async: false,
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                        }
                    }
                })



            }
        });

        var dt_agency = $("table#dt_agency").DataTable({

            "destroy": true,
            "ajax": {
                url: "functionquery.php?request=agency_list",
                type: "POST"
            },
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt_agency').on('click', 'a.action', function() {

            var id = this.id.split("_");
            action = id[0];
            agency_code = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_agency.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            var messej = "";
            if (action == 'delete') {
                messej = "Are you sure you want to delete this agency?";
            } else {
                messej = `Are you sure you want to ${action} this agency`;
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

                        if (action == 'delete') {

                            $.post("functionquery.php?request=delete_agency", {
                                agency_code: agency_code
                            }, function(data, status) {

                                var response = data.trim();
                                if (response == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Agency has been deleted.",
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("functionquery.php?request=agency_status", {
                                action,
                                agency_code
                            }, function(data, status) {

                                var response = data.trim();
                                if (response == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Agency has been ${action}.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            });
        });

        $('table#dt_agency').on('click', 'a.update_agency', function() {

            var id = this.id.split("_");
            agency_code = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_agency.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $("div#updateAgency").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#updateAgency").modal("show");
            $("div.updateAgency").html("");

            $.ajax({
                type: "POST",
                url: "functionquery.php?request=update_agency_form",
                data: {
                    agency_code: agency_code
                },
                success: function(data) {

                    $("div.updateAgency").html(data);
                }
            });

        });

        $("button.update_agency").click(function() {

            var agency = $("input[name = 'agency_name']").val();
            var agency_code = $("input[name = 'agency_code']").val();
            if (agency.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Agency Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (agency.trim() == "") {

                                $("input[name = 'agency_name']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=updateAgency",
                    data: {
                        agency: agency,
                        agency_code: agency_code
                    },
                    success: function(data) {

                        var response = data.trim();
                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Agency has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        var dt_company = $("table#dt-company").DataTable({

            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "functionquery.php?request=company_list"
            },
            "order": [],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-company').on('click', 'a.action', function() {

            var id = this.id.split("_");
            action = id[0];
            company_code = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            var messej = "";
            if (action == 'delete') {
                messej = "Are you sure you want to delete this company?";
            } else {
                messej = `Are you sure you want to ${action} this company`;
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

                        if (action == 'delete') {

                            $.post("functionquery.php?request=delete_pc_name", {
                                company_code
                            }, function(data, status) {

                                var response = data.trim();
                                if (response == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Company has been deleted.",
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("functionquery.php?request=company_status", {
                                action,
                                company_code
                            }, function(data, status) {

                                var response = data.trim();
                                if (response == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Company has been ${action}.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            });
        });

        $('table#dt-company').on('click', 'a.update_company', function() {

            var id = this.id.split("_");
            company_code = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $("div#update-company").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#update-company").modal("show");
            $("div.update-company").html("");

            $.ajax({
                type: "GET",
                url: "functionquery.php?request=update_company_form",
                data: {
                    company_code
                },
                success: function(data) {

                    $("div.update-company").html(data);
                }
            });

        });

        $("button.update_company").click(function() {

            var company = $("input[name = 'company']").val();
            var company_code = $("input[name = 'company_code']").val();
            if (company.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Company Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (agency.trim() == "") {

                                $("input[name = 'company']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=update_company",
                    data: {
                        company,
                        company_code
                    },
                    success: function(data) {

                        var response = data.trim();
                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Company has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Company is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button.add_company").click(function() {
            var company = $("input[name = 'company']").val();
            if (company.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Company Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (company.trim() == "") {

                                $("input[name = 'company']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=add_company",
                    data: {
                        company
                    },
                    success: function(data) {

                        var response = data.trim();
                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Company has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Company is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button.add_product").click(function() {
            var product = $("input[name = 'product']").val();
            if (product.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up product Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (product.trim() == "") {

                                $("input[name = 'product']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=add_product",
                    data: {
                        product
                    },
                    success: function(data) {

                        var response = data.trim();
                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Product has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Product is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        var dt_products = $("table#dt-products").DataTable({

            "destroy": true,
            "ajax": {
                url: "functionquery.php?request=product_lists"
            },
            "order": [],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-products').on('click', 'a.action', function() {

            var id = this.id.split("_");
            action = id[0];
            product_id = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_products.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            var messej = "";
            if (action == 'delete') {
                messej = "Are you sure you want to delete this product?";
            } else {
                messej = `Are you sure you want to ${action} this product`;
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

                        if (action == 'delete') {

                            $.post("functionquery.php?request=delete_product", {
                                product_id
                            }, function(data, status) {

                                var response = data.trim();
                                if (response == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Company has been deleted.",
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("functionquery.php?request=product_status", {
                                action,
                                product_id
                            }, function(data, status) {

                                var response = data.trim();
                                if (response == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Product has been ${action}.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            });
        });

        $('table#dt-products').on('click', 'a.update_product', function() {

            var id = this.id.split("_");
            product_id = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_products.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $("div#update-product").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#update-product").modal("show");
            $("div.update-product").html("");

            $.ajax({
                type: "GET",
                url: "functionquery.php?request=update_product_form",
                data: {
                    product_id
                },
                success: function(data) {

                    $("div.update-product").html(data);
                }
            });

        });

        $("button.update_product").click(function() {

            var product = $("input[name = 'product_name']").val();
            var product_id = $("input[name = 'product_id']").val();
            if (product.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Product Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (agency.trim() == "") {

                                $("input[name = 'product_name']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=update_product",
                    data: {
                        product,
                        product_id
                    },
                    success: function(data) {

                        var response = data.trim();
                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Product has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Product is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        var dt_company_product = $("table#dt_company_product").DataTable({

            "destroy": true,
            "ajax": {
                url: "functionquery.php?request=company_product",
                type: "GET"
            },
            "order": [
                [0, "asc"],
                [1, "asc"]
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt_company_product').on('click', 'i.delete_product', function() {

            var id = this.id.split("_");
            product_id = id[1];

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company_product.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to delete this product?",
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $.post("functionquery.php?request=delete_company_product", {
                            product_id
                        }, function(data, status) {

                            var response = data.trim();
                            if (response == "success") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Company has been deleted.",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            var dt_company_product = $("table#dt_company_product").DataTable({

                                                "destroy": true,
                                                "ajax": {
                                                    url: "functionquery.php?request=company_product",
                                                    type: "GET"
                                                },
                                                "order": [
                                                    [0, "asc"],
                                                    [1, "asc"]
                                                ],
                                                "columnDefs": [{
                                                    "targets": [2],
                                                    "orderable": false,
                                                    "className": "text-center",
                                                }]
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            });
        });

        $("button.setup_product_btn").click(function() {

            $("div#setup_company_product").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#setup_company_product").modal("show");
            $("div.companies").html("");

            $.ajax({
                type: "POST",
                url: "functionquery.php?request=selectCompany",
                success: function(data) {

                    $("div.product_list").html(data);
                }
            });
        });

        $("button.submit_products").click(function() {

            var company = $("select[name = 'setupan_product']").val();
            var chk = $("input[name = 'chkProd[]']");
            var newCHK = "";

            for (var i = 0; i < chk.length; i++) {

                if (chk[i].checked == true) {

                    newCHK += chk[i].value + "|_";
                }
            }

            $.post("functionquery.php?request=insert_update_company_product", {
                company,
                newCHK
            }, function(data, status) {

                var response = data.trim();
                if (response == "success") {

                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',
                        cancel: false,
                        content: "Company Product has been setup!",
                        buttons: {
                            OK: 'Yes'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                $("div#setup_company_product").modal("hide");
                                location.reload();
                            }
                        }
                    });
                } else {

                    console.log(response);
                }
            });
        });

        //Date picker
        $('.datepicker').datepicker({

            changeYear: true,
            changeMonth: true
        });

        $("form#import_file").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $("button.button1").hide();
            $("button.button2").show();

            var ext = $("input[name = 'textfile']").val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['text', 'txt', 'csv']) == -1) {

                $.alert.open({
                    type: 'warning',
                    title: 'Info',
                    icon: 'confirm',
                    cancel: false,
                    content: 'Text/CSV file is only allow',
                    buttons: {
                        OK: 'Yes'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            $("input[name = 'textfile']").val('');
                            $("span.button1").show();
                            $("span.button2").hide();
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "functionquery.php?request=import_file",
                    type: 'POST',
                    data: formData,
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

                                    location.reload();
                                }
                            }
                        });
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });
    });

    /* Formatting function for row details - modify as you need */
    function format(d) {
        // `d` is the original data object for the row
        return '<table class="table table-hover table-bordered" width="100%" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
            '<tr>' +
            '<td width="20%">Business Unit:</td>' +
            '<td width="30%">' + d[5] + '</td>' +
            '<td width="20%">Position:</td>' +
            '<td width="30%">' + d[8] + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Department:</td>' +
            '<td>' + d[6] + '</td>' +
            '<td>Deployment:</td>' +
            '<td>' + d[9] + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Company:</td>' +
            '<td>' + d[7] + '</td>' +
            '<td>Inclusive Date:</td>' +
            '<td>' + d[10] + '</td>' +
            '</tr>' +
            '</table>';
    }

    $(document).ready(function() {
        var table = $('#dutySched_table').DataTable({

            "ajax": {
                url: "functionquery.php?request=dutySched",
                type: "post",
                data: {
                    store: "<?php echo @$_GET['store']; ?>",
                    department: "<?php echo @$_GET['department']; ?>",
                    company: "<?php echo @$_GET['company']; ?>",
                    status: "<?php echo @$_GET['status']; ?>"
                },
            },
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {
                    "data": "0"
                },
                {
                    "data": "1"
                },
                {
                    "data": "2"
                },
                {
                    "data": "3"
                },
                {
                    "data": "4"
                }
            ],
            "order": [
                [1, 'asc']
            ]
        });

        // Add event listener for opening and closing details
        $('#dutySched_table tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });

    function company_list(agency_code) {

        if (agency_code.trim() != "") {

            $("div.companies").html('<img src="images/icon/loading.gif"> <span>Please Wait...</span>');
            $.ajax({
                type: "POST",
                url: "functionquery.php?request=setupan_agency",
                data: {
                    agency_code: agency_code
                },
                success: function(result) {

                    $("div.companies").html(result);
                }
            });
        } else {

            $("div.companies").html('');
        }
    }

    // newly updated about products
    function select_product_list(company) {

        if (company.trim() != "") {

            $("div.products").html('<img src="images/icon/loading.gif"> <span>Please Wait...</span>');
            $.ajax({
                type: "POST",
                url: "functionquery.php?request=setupan_company_product",
                data: {
                    company
                },
                success: function(result) {

                    $("div.products").html(result);
                }
            });
        } else {

            $("div.products").html('');
        }
    }

    function chkProduct(x) {

        if ($("input.chkP_" + x).is(':checked')) {

            $("input.chkAP_" + x).prop("checked", true);
        } else {

            $("input.chkAP_" + x).prop("checked", false);
        }
    }

    function chk(x) {

        if ($("input.chk_" + x).is(':checked')) {

            $("input.chkA_" + x).prop("checked", true);
        } else {

            $("input.chkA_" + x).prop("checked", false);
        }
    }

    function getdefault(code) {

        var empId = $("[name = 'empId']").val();

        $.ajax({
            type: "POST",
            url: "employee_information_details.php?request=" + code,
            data: {
                empId: empId
            },
            success: function(data) {

                $("#details").html(data);
            }
        });
    }

    //get company details
    function getBusinessUnit(id) {

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
    }

    function getDepartment(id) {

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
    }

    function getSection(id) {

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
    }

    function getSubSection(id) {

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
    }

    function getUnit(id) {

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
    }

    function searchApp(lastname, firstname) {

        if (lastname == "") {

            alert("Please Fill-up Required Fields!");
            $("[name = 'lastnameApp']").css("border-color", "#dd4b39");

        } else {

            window.location = "?p=searchApplicant&&module=Promo&&firstname=" + firstname + "&&lastname=" + lastname;
        }
    }

    function searchPromo() {

        var search = $("#searchPromo").val();
        var loc = document.location;

        window.location = "?p=search&&module=Promo&&search=" + search;
    }

    //load blacklists
    var dataTable = $('table#blacklists').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [1, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadBlacklists", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#blacklists").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                $("#blacklists_processing").css("display", "none");

            }
        }
    });

    //load for manage promo incharge account
    var dataTable = $('table#managePromoIncharge').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [1, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadManagePromoIncharge", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#managePromoIncharge").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                $("#managePromoIncharge_processing").css("display", "none");

            }
        }
    });

    //load for manage promo account
    var dataTable = $('table#managePromo').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [0, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadManagePromo", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#managePromo").append('<tbody class="employee-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
                $("#managePromo_processing").css("display", "none");

            }
        }
    });

    //load for logs
    var dataTable = $('table#logs').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [0, 'desc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadLogs", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#logs").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                $("#logs_processing").css("display", "none");

            }
        }
    });

    //load for logs admin

    var filterDate = $("[name = 'dateFilter']").val();
    var dataTable = $('table#logsAdmin').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [0, 'desc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadlogsAdmin", // json datasource
            type: "post", // method  , by default get
            data: {
                filter: filterDate
            },
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#logsAdmin").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                $("#logsAdmin_processing").css("display", "none");

            }
        }
    });

    //load for masterfile

    var store = $("[name = 'tempStore']").val();
    var department = $("[name = 'tempDepartment']").val();
    var promoType = $("[name = 'tempPromoType']").val();
    var company = $("[name = 'tempCompany']").val();
    var status = $("[name = 'tempStatus']").val();

    var dataTable = $('table#masterfile').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [1, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadMasterfile", // json datasource
            type: "post", // method  , by default get
            data: {
                store: store,
                department: department,
                promoType: promoType,
                company: company,
                status: status
            },
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#masterfile").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
                $("#masterfile_processing").css("display", "none");
            }
        }
    });

    // load for EOC List 

    var filterBU = $("[name = 'filterBU']").val();
    var filterDate = $("[name = 'filterDate']").val();
    var filterMonth = $("[name = 'filterMonth']").val();
    var filterYear = $("[name = 'filterYear']").val();

    if (filterMonth != '') {
        $("[name = 'filterYear']").show();
    }

    var dataTable = $('table#eocList').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [2, 'desc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadEOCList", // json datasource
            type: "post", // method  , by default get
            data: {
                filterBU: filterBU,
                filterDate: filterDate,
                filterMonth: filterMonth,
                filterYear: filterYear
            },
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#masterfile").append('<tbody class="employee-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
                $("#masterfile_processing").css("display", "none");
            }
        }
    });

    //load for resignation/termination list
    var dataTable = $('table#resignationList').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [0, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadResignationList", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#resignationList").append('<tbody class="employee-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                $("#resignationList_processing").css("display", "none");

            }
        }
    });

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#ccc");
    }

    function select_product(company_code) {

        if (company_code != '') {

            $.ajax({
                type: "GET",
                url: "functionquery.php?request=load_products",
                data: {
                    company_code
                },
                success: function(data) {

                    $("div.product_select").html(data);
                }
            });
        } else {

            $("div.product_select").html('');
        }
    }

    function selectProduct(company_code) {

        if (company_code != '') {

            $.ajax({
                type: "GET",
                url: "employee_information_details.php?request=product_list",
                data: {
                    company_code
                },
                success: function(data) {

                    $("select[name = 'company']").css("border-color", "#ccc");
                    $("select[name = 'product']").html(data);
                    $("select[name = 'product']").prop("disabled", false);
                }
            });
        } else {

            $("select[name = 'product']").prop("disabled", true);
        }
    }

    function selectCompanyProduct(company_code) {

        var company_products = $("[name = 'product']").val();
        if (company_code != '') {

            $.ajax({
                type: "POST",
                url: "functionquery.php?request=loadProducts",
                data: {
                    company: company_code,
                    product: company_products
                },
                success: function(data) {
                    $("select[name = 'company_select']").css("border-color", "#ccc");
                    $("div.product_select").show();
                    $("div.product_select").html(data);
                }
            });
        } else {

            $("select[name = 'product_select']").prop("disabled", true);
        }
    }

    // load for new promo list
    var dataTable = $('table#newPromo').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [0, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadNewPromo", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#newPromo").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
                $("#newPromo_processing").css("display", "none");

            }
        }
    });

    // load for birthday today list
    var dataTable = $('table#birthdayToday').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [0, 'asc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadBirthdayToday", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#birthdayToday").append('<tbody class="employee-grid-error"><tr><th colspan="7">No data found in the server</th></tr></tbody>');
                $("#birthdayToday_processing").css("display", "none");

            }
        }
    });

    // load for failed epas list
    var dataTable = $('table#failedEpas').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [
            [2, 'desc']
        ],

        "ajax": {
            url: "functionquery.php?request=loadFailedEpas", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                $(".employee-grid-error").html("");
                $("table#failedEpas").append('<tbody class="employee-grid-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
                $("#failedEpas_processing").css("display", "none");

            }
        }
    });
</script>