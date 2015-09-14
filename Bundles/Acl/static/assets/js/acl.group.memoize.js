'use strict';

function GroupModalMemoization(){
    var self = this;

    var cached = {};

    self.hasMember = function(memberId){
        return !!cached[memberId];
    };

    self.saveMember = function(memberId, data){
        cached[memberId] = data;
    };

    self.getMember = function(memberId){
        return cached[memberId];
    };
}
