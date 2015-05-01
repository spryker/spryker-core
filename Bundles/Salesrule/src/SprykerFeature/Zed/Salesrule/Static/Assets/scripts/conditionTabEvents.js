$(function () {
    $('#manage-codes-tab').click(function() {
        var url = '/salesrule/code-ajax/index?id-code-pool=' + $('#number').val() + '&id-sales-rule=' + $('#id-sales-rule').html();
        $.ajax({
            url: url,
            method: 'GET'
        }).success(function(data){
                $('#manage-codes').html(data);
            }).fail(function(status) {
                console.log(status);
            });
    });

    $('#create-code-pool-tab').click(function() {
        $('#create-code-pool').load('/salesrule/code-pool-form/index?id-sales-rule=' + $('#id-sales-rule').html());
    });

    $('#edit-code-pool-tab').click(function() {
        $('#edit-code-pool').load('/salesrule/code-pool-form/index?id-code-pool=' + $('#number').val() + '&id-sales-rule=' + $('#id-sales-rule').html());
    });

    var addCodes = $('#add-codes').html();

    if ($('#number').val() == null) {
        $('#codes a[href="#edit-code-pool"]').hide();
        $('#codes a[href="#manage-codes"]').hide();
        $('#ConditionVoucherCodeInPool input[id=Save]').attr('disabled', 'disabled');
        $("#create-code-pool-tab").click();
        $('#codes a[href="#create-code-pool"]').tab('show');
    } else {
        var idCodePool = $('#id-code-pool').html();
        if (idCodePool) {
            $('#number').val(idCodePool);
        }
        if (addCodes == 1 || window.openTab) {
            $('#codes a[href="#manage-codes"]').tab('show');
            $('#'+ window.openTab).click();
        } else {
            $('#codes a:first').tab('show');
        }
    }

    $("#number" ).on('change focus', function() {
        if (!$("#number").val() || $("#condition_name").val() !== 'ConditionVoucherCodeInPool') {
            return;
        }
        $.ajax({
            url: '/salesrule/code-pool-ajax/info?id-code-pool=' + $("#number").val(),
            method: 'GET'
        }).success(function(data){
                $('#help').html(data);
            }).fail(function(status) {
                console.log(status);
            });
    });

    $('#number').focus();
});
