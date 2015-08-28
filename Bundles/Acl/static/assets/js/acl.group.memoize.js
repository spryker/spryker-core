'use strict';

function GroupModalMemoization(){
    var self = this;

    self.cached = [];

    self.hasMember = function(memberId){
        return (memberId in self.cached);
    };

    self.saveMember = function(memberId, data){
        self.cached[memberId] = data;
    };

    self.getMember = function(memberId){
        return self.cached[memberId];
    };
}
