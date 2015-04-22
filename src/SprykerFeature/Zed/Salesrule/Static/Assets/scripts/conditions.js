$(function() {
    $(document)
        .on({
            click: function(ev) {
                ev.preventDefault();
                $.ajax({
                    url: ev.target.href,
                    method: 'GET'
                }).success(function(data){
                        var id = $(ev.target).data("ajax-update");
                        $(id).html(data);
                    }).fail(function(status) {
                        window.alert('Could not get sales rule condition form!');
                        console.log(status);
                    });
            }
        }, "a[data-ajax-update]")
        .on({
            click: function(ev) {
                ev.preventDefault();
                $.ajax({
                    url: ev.target.href,
                    method: 'GET'
                }).success(function(data){
                        var id = $(ev.target).data("ajax-edit-condition");
                        $(id).html(data);
                    }).fail(function(status) {
                        window.alert('Could not get sales rule condition form!');
                        console.log(status);
                    });
            }
        }, "a[data-ajax-edit-condition]")
        .on({
            submit: function(ev) {
                ev.preventDefault();
                var $target = $(ev.target);
                $.ajax({
                    url: ev.target.action,
                    method: ev.target.method,
                    data: $target.serialize()
                }).success(function(data) {
                        $('#sales-rule-condition-form').html(data);
                        var grid = $("#condition-grid").data("kendoGrid");
                        grid.dataSource.read();
                        grid.refresh();
                    }).fail(function(status) {
                        console.log(status);
                    });
            }
        }, "form[data-ajax-form]")
    ;
});
