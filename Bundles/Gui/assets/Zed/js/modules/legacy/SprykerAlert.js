/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

module.exports = new function() {
    var self = this;
    self.init = function(){
        self.clean();
    };
    self.success = function(message){
        var data = {
            "message": message,
            "title": "Success"
        };
        self.clangeClass('alert-success');
        self.displayAlert(data);
    };
    self.error = function(message) {
        var data = {
            "message": message,
            "title": "Error"
        };
        self.clangeClass('alert-danger');
        self.displayAlert(data);
    };
    self.info = function(message){
        return self.custom(message);
    };
    self.custom = function(message, title){
        var data = {
            "message": message,
            "title": title || "Info"
        };
        self.clangeClass('alert-info');
        self.displayAlert(data);
    };
    self.clean = function() {
        $('#modal-content').removeClass([
            'alert-success',
            'alert-danger',
            'alert-info',
            'alert-warning'
        ]);
        $('#modal-title').html('');
        $('#modal-body').html('');
    };
    self.clangeClass = function(className){
        self.clean();
        $('#modal-content').addClass(className);
    };
    self.displayAlert = function(options){
        $('#modal-title').html(options.title);
        $('#modal-body').html(options.message);
        self.show();
    };
    self.show = function(){
        $('#modal-alert').modal('show');
    };
    self.init();
};
