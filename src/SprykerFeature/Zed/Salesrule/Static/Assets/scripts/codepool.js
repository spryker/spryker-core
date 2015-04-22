$('form[data-ajax-code-pool-form]').submit(function(ev) {
    ev.preventDefault();
    var $target = $(ev.target);

    $.ajax({
        url: ev.target.action,
        method: ev.target.method,
        data: $target.serialize()
    })
    .success(function(data) {
        if(data.success) {
            var grid = $("#condition-grid").data("kendoGrid");
            grid.dataSource.read();
            grid.refresh();
            var idCodePool = data.idCodePool;
            var url = data.url;
            $.ajax({
                url: url,
                method: 'GET'
            }).success(function(data){
                $('#sales-rule-condition-form').html(data);
            });
        }
    });
});
