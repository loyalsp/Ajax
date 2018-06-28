<?php
/**
 * template_scripts.php
 *
 * Author: pixelcave
 *
 * All vital JS scripts are included here
 *
 */
?>
<!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file (Remove 'http:' if you have SSL) -->
<script src="js/vendor/jquery-1.11.0.min.js"></script>
<script>!window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-1.11.0.min.js"%3E%3C/script%3E'));</script>

<!-- Bootstrap.js, Jquery plugins and Custom JS code -->
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>


<link rel="stylesheet" href="css/buttons.dataTables.min.css">
<script src="js/vendor/jquery.dataTables.min.js"></script>
<script src="js/vendor/dataTables.buttons.min.js"></script>
<script src="js/vendor/jszip.min.js"></script>
<script src="js/vendor/pdfmake.min.js"></script>
<script src="js/vendor/vfs_fonts.js"></script>
<script src="js/vendor/buttons.html5.min.js"></script>

<script src="js/app.js"></script>
<script src="js/pages/formsAjax.js"></script>

<script src="js/vendor/bootstrap-multiselect.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-multiselect.css">


<script>

    // Datatables Bootstrap Integration (Pagination)
    jQuery.fn.dataTableExt.oApi.fnPagingInfo = function (e) {
        return {
            iStart: e._iDisplayStart,
            iEnd: e.fnDisplayEnd(),
            iLength: e._iDisplayLength,
            iTotal: e.fnRecordsTotal(),
            iFilteredTotal: e.fnRecordsDisplay(),
            iPage: Math.ceil(e._iDisplayStart / e._iDisplayLength),
            iTotalPages: Math.ceil(e.fnRecordsDisplay() / e._iDisplayLength)
        }
    }, jQuery.extend(jQuery.fn.dataTableExt.oPagination, {
        bootstrap: {
            fnInit: function (e, t, n) {
                var i = e.oLanguage.oPaginate, r = function (t) {
                    t.preventDefault(), e.oApi._fnPageChange(e, t.data.action) && n(e)
                };
                jQuery(t).append('<ul class="pagination pagination-sm remove-margin"><li class="prev disabled"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i> ' + i.sPrevious + "</a></li>" + '<li class="next disabled"><a href="javascript:void(0)">' + i.sNext + ' <i class="fa fa-chevron-right"></i></a></li>' + "</ul>");
                var o = jQuery("a", t);
                jQuery(o[0]).bind("click.DT", {action: "previous"}, r), jQuery(o[1]).bind("click.DT", {action: "next"}, r)
            }, fnUpdate: function (e, t) {
                var n, i, r, o, a, s = 5, l = e.oInstance.fnPagingInfo(), c = e.aanFeatures.p, u = Math.floor(s / 2);
                for (l.iTotalPages < s ? (o = 1, a = l.iTotalPages) : l.iPage <= u ? (o = 1, a = s) : l.iPage >= l.iTotalPages - u ? (o = l.iTotalPages - s + 1, a = l.iTotalPages) : (o = l.iPage - u + 1, a = o + s - 1), n = 0, iLen = c.length; iLen > n; n++) {
                    for (jQuery("li:gt(0)", c[n]).filter(":not(:last)").remove(), i = o; a >= i; i++) r = i === l.iPage + 1 ? 'class="active"' : "", jQuery("<li " + r + '><a href="javascript:void(0)">' + i + "</a></li>").insertBefore(jQuery("li:last", c[n])[0]).bind("click", function (n) {
                        n.preventDefault(), e._iDisplayStart = (parseInt(jQuery("a", this).text(), 10) - 1) * l.iLength, t(e)
                    });
                    0 === l.iPage ? jQuery("li:first", c[n]).addClass("disabled") : jQuery("li:first", c[n]).removeClass("disabled"), l.iPage === l.iTotalPages - 1 || 0 === l.iTotalPages ? jQuery("li:last", c[n]).addClass("disabled") : jQuery("li:last", c[n]).removeClass("disabled")
                }
            }
        }
    });


    var myVar = setInterval(function () {
        setSession()
    }, 300000);

    function setSession() {

        $.ajax({
            type: "POST",
            url: "ajax/session.php",
            data: {set: 1}
        })

    }

    function selectAll(selector) {

        if ($(selector).eq(0).prop('checked'))
            $(selector).prop('checked', false)
        else
            $(selector).prop('checked', true)

    }

    function setClientSendingServer(userid, esp_setup_id, server_id) {
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: 'setup_server', userid: userid, esp_setup_id: esp_setup_id, server_id: server_id}
        }).done(function (msg) {

            if (msg == 'unassing_ip')
                alert('Remove assigned ips on existing server before changing.')


        });

    }


    function GeneratePMTAConfigOnly() {
        $("#modal-loading").modal('show');
        $("#snackbar").modal('hide');
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: 'generate_only_config'},
            success: function (data) {
                $("#modal-loading").modal('hide');
                showToaster(data);
            }

        })
    }

    function GeneratePMTAConfig() {
        ids = $('input[name="server_ids[]"]').map(function () {
            return $(this).val();
        }).get();
        $("#btn-m").trigger("click");
        $("#server_status").html('');
        total_ids = ids.length;
        time = 500;
        console.log(total_ids);
        for (i = 0; i < total_ids; i++) {
            server_no = i + 1;
            config_id = 'config_id_' + i;
            dkim_id = 'dkim_id_' + i;
            connect_id = 'connect_id_' + i;
            copy_id = 'copy_id_' + i;
            reset_id = 'reset_id_' + i;
            string = '<tr>\n' +
                '                                <td >Generatring Config File</td>\n' +
                '                                <td id="' + config_id + '"><i class="fa fa-gear fa-spin fa-2x text-info loader-4"></i></td>\n' +
                '                            </tr>\n' +
                '                            <tr>\n' +
                '                                <td >Generatring Dkim Files</td>\n' +
                '                                <td id="' + dkim_id + '"><i class="fa fa-gear fa-spin fa-2x text-info loader-4" ></i></td>\n' +
                '                            </tr>\n' +
                '                            <tr>\n' +
                '                                <td >Connecting to server ' + '</td>\n' +
                '                                <td id="' + connect_id + '"><i class="fa fa-gear fa-spin fa-2x text-info loader-4" ></i></td>\n' +
                '                            </tr>\n' +
                '                            <tr>\n' +
                '                                <td >Copying Files</td>\n' +
                '                                <td id="' + copy_id + '"><i class="fa fa-gear fa-spin fa-2x text-info loader-4" ></i></td>\n' +
                '                            </tr>\n' +
                '                            <tr>\n' +
                '                                <td >Restarting Server</td>\n' +
                '                                <td id="' + reset_id + '"><i class="fa fa-gear fa-spin fa-2x text-info loader-4" ></i>\n' +
                '                                </td>\n' +
                '                            </tr>';

            string = '<tr><td><h4 class="success-update">Server ' + server_no + ' Status</h4></td></tr>' + string;
            $("#server_status").append(string).fadeIn();
        }
      startProcess(ids,0,"generate_config_file","#config_id_",null);
    }

    function resetServer(id, action, i, frontEndIdentifier, time) {
        runAjax(id, action, i, frontEndIdentifier, time);
    }

    function copyToPmta(id, action, i, frontEndIdentifier, time) {
        runAjax(id, action, i, frontEndIdentifier, time);
    }

    function generateConfigfile(id, action, i, frontEndIdentifier, time) {
        runAjax(id, action, i, frontEndIdentifier, time);
    }

    function connectToServer(id, action, i, frontEndIdentifier, time) {
        runAjax(id, action, i, frontEndIdentifier, time);
    }

    function generateDkimFiles(id, action, i, frontEndIdentifier, time) {
        runAjax(id, action, i, frontEndIdentifier, time);
    }

   function startProcess(ids,index,action,flag) {
        id= ids[index];
       runAjax(ids,index,action,frontEndIdentifier,flag)
   }
    function runAjax(ids, index, action, frontEndIdentifier, flag) {
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: action, 'id': id[index]},
            dataType: 'json',
            success: function (data) {
                status = frontEndIdentifier + i;
                $(status).html('');
                if (data.status == 'success') {
                    $(status).html('<i class="hi hi-ok text-success"></i>');
                        if(action=='generate_config_file')
                        runAjax(ids, id, "generate_dkim_files", "#dkim_id_", null);
                        else if(action=='generate_dkim_files')
                            runAjax(ids, id, "connect_server", "#connect_id_", null);
                            else if(action=='connect_server')
                            runAjax(ids, id, "copy_to_pmta", "#copy_id_", null);
                            else if(action=='copy_to_pmta'
                            runAjax(ids, id, "reset_pmta", "#reset_id_", null);
                            else {
                            index = index + 1;
                            if (typeof ids[index] !== 'undefined') {
                                startProcess(ids,index,"generate_config_file","#config_id_",null);
                            }
                        }
                }
                else {
                    $(status).html('<i class="hi hi-remove text-danger"></i>');
                    if(action=='generate_config_file')
                        runAjax(ids, id, "generate_dkim_files", "#dkim_id_", null);
                    else if(action=='generate_dkim_files')
                        runAjax(ids, id, "connect_server", "#connect_id_", null);
                    else if(action=='connect_server')
                        runAjax(ids, id, "copy_to_pmta", "#copy_id_", null);
                    else if(action=='copy_to_pmta'
                        runAjax(ids, id, "reset_pmta", "#reset_id_", null);
                    else {
                        index = index + 1;
                        if (typeof ids[index] !== 'undefined') {
                            startProcess(ids,index,"generate_config_file","#config_id_",null);
                        }
                    }
                }
            }
        });
    }

    function deleteLocalFiles(id) {
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: 'delete_files', 'id': id}

        })
    }

    function DeactivateClient(userid) {
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: 'deactivate', userid: userid}
        }).done(function () {
            $('.actions_' + userid).toggle();
        });

    }

    function ActivcateClient(userid) {
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: 'activate', userid: userid}
        }).done(function () {
            $('.actions_' + userid).toggle();
        });

    }

    function DeactivateSender(account_id, setup_id) {
        $.ajax({
            type: "POST",
            url: "ajax/esp_status.php",
            data: {action: 'deactivate', account_id: account_id, setup_id: setup_id}
        }).done(function () {
            $('.actions_' + setup_id).toggle();
        });
    }

    function ActivateSender(account_id, setup_id) {
        $.ajax({
            type: "POST",
            url: "ajax/esp_status.php",
            data: {action: 'activate', account_id: account_id, setup_id: setup_id}
        }).done(function () {
            $('.actions_' + setup_id).toggle();
        });
    }

    function DeleteSender(account_id, setup_id) {
        if (confirm('Are you sure to delete sender information?')) {
            $.ajax({
                type: "POST",
                url: "ajax/esp_status.php",
                data: {action: 'delete', account_id: account_id, setup_id: setup_id}
            }).done(function () {
                $('.actions_' + setup_id).toggle();
            });

            $('#esp_setup' + setup_id).hide();
        }
    }

    function multiSelect() {
        $('.multi-select').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '100%'
        });
    }

    function saveOnlyConfig() {
        ids = $('input[name="server_ids[]"]').map(function () {
            return $(this).val();
        }).get();
        $("#btn-m").trigger("click");
        $("#server_status").html('');
        total_ids = ids.length;
        time = 500;
        console.log(total_ids);
        for (i = 0; i < total_ids; i++) {
            server_no = i + 1;
            config_id = 'config_id_' + i;

            string = '<tr>\n' +
                '                                <td >Saving Configuration</td>\n' +
                '                                <td id="' + config_id + '"><i class="fa fa-gear fa-spin fa-2x text-info loader-4"></i></td>\n' +
                '                            </tr>';

            string = '<tr><td colspan="2"><h4 class="success-update">Server ' + server_no + ' Status</h4></td></tr>' + string;
            $("#server_status").append(string).fadeIn();
         saveOnlyConfig_(ids[i],config_id)
        }

    }
    function saveOnlyConfig_(id,identifier) {
        $.ajax({
            type: "POST",
            url: "ajax/account_status.php",
            data: {action: 'save_config_only', 'id': id},
            dataType: 'json',
            success: function (data) {
                if (data.status == 'Configuration saved.') {
                    config_id = '#'+identifier;
                    $(config_id).html('');
                    $(config_id).html('<i class="hi hi-ok text-success"></i>');
                   // return true;
                }
                else {
                    config_id = '#'+identifier;
                    $(config_id).html('');
                    $(config_id).html('<i class="hi hi-remove text-danger"></i>'+'<span class="error-update">'+data.status+'</span>');

                }
            }
        });
        return false;
    }
</script>
