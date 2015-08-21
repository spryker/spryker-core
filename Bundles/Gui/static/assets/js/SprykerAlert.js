'use strict';

function SprykerAlert() {
    this.init = function(){
        this.clean();
    };
    this.success = function(message){
        var data = {
            "message": message,
            "title": "Success"
        };
        this.clangeClass('alert-success');
        this.displayAlert(data);
    };
    this.error = function(message) {
        var data = {
            "message": message,
            "title": "Error"
        };
        this.clangeClass('alert-danger');
        this.displayAlert(data);
    };
    this.info = function(message){
        var data = {
            "message": message,
            "title": "Info"
        };
        this.clangeClass('alert-info');
        this.displayAlert(data);
    };
    this.clean = function() {
        $('#modal-content').removeClass([
            'alert-success',
            'alert-danger',
            'alert-info',
            'alert-warning'
        ]);
        $('#modal-title').html('');
        $('#modal-body').html('');
    };
    this.clangeClass = function(className){
        this.clean();
        $('#modal-content').addClass(className);
    };
    this.displayAlert = function(options){
        $('#modal-title').html(options.title);
        $('#modal-body').html(options.message);
        $('#modal-alert').modal('show');
    };
    this.init();
}
