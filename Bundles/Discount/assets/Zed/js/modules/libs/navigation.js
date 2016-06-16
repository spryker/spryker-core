'use strict';

function DiscountNavigation(){
    this.currentTabPosition = 0;
    this.currentUrlHash = window.location.hash;
    this.tabUrls = $('#discount-tabs').find('li a');

    this.checkActivatedTab();
    this.changeTabsOnClick();
    this.showHideNavigationButtons();
    this.listenNavigationButtons();
}

DiscountNavigation.prototype.listenNavigationButtons = function(){
    var self = this;
    $('#btn-tab-previous').on('click', function(event){
        event.preventDefault();
        var element = $(this);
        var hash = element.attr('hash');

        self.currentTabPosition = Math.max(0, self.currentTabPosition - 1);
        self.currentUrlHash = hash;

        self.activateTab(element, hash);
        self.navigateElement();
        self.showHideNavigationButtons();
    });
    $('#btn-tab-next').on('click', function(event){
        event.preventDefault();
        var element = $(this);
        var hash = element.attr('href');

        self.currentTabPosition = Math.min(self.tabUrls.length, self.currentTabPosition + 1);
        self.currentUrlHash = hash;

        self.activateTab(element, hash);
        self.navigateElement();
        self.showHideNavigationButtons();
    });
};

DiscountNavigation.prototype.changeTabsOnClick = function(){
    var self = this;
    self.tabUrls.on('click', function(event){
        event.preventDefault();
        var selectedElement = $(this);
        var hash = selectedElement.attr('href');

        var position = 0;
        var positionChanged = false;

        self.tabUrls.each(function(){
            var element = $(this);
            if (positionChanged === false && element.attr('href') === selectedElement.attr('href')) {
                self.currentTabPosition = position;
                self.currentUrlHash = hash;

                positionChanged = true;
                self.activateTab(element, self.currentUrlHash);
                self.showHideNavigationButtons();
            }
            position++
        });
    });
};

DiscountNavigation.prototype.proceedChange = function(element, hash){
    this.activateTab(element, hash);
    this.checkActivatedTab();
};

DiscountNavigation.prototype.checkActivatedTab = function(){
    var self = this;
    var position = 0;
    var positionChanged = false;
    self.tabUrls.each(function(){
        var element = $(this);
        if (positionChanged === false && element.attr('href') === self.currentUrlHash) {
            self.currentTabPosition = position;

            positionChanged = true;
            self.activateTab(element, self.currentUrlHash);
            self.showHideNavigationButtons();
        }
        position++
    });
};

DiscountNavigation.prototype.activateTab = function(element, hash){
    window.location.hash = hash;
    var discountForm = $('#discount-form');
    var action = discountForm.attr('action');
    discountForm.attr('action', hash);
    element.tab('show');
};

DiscountNavigation.prototype.navigateElement = function(){
    var element = $('#discount-tabs').find('li:eq(' + this.currentTabPosition + ') a');
    var hash = element.attr('href');

    this.proceedChange(element, hash);
};

DiscountNavigation.prototype.showHideNavigationButtons = function(){
    var self = this;

    if (self.currentTabPosition === 0) {
        $('#btn-tab-previous').addClass('hidden');
        $('#btn-tab-next').removeClass('hidden');
    } else  if (self.currentTabPosition === (self.tabUrls.length - 1)) {
        $('#btn-tab-previous').removeClass('hidden');
        $('#btn-tab-next').addClass('hidden');
    } else {
        $('#btn-tab-previous').removeClass('hidden');
        $('#btn-tab-next').removeClass('hidden');
    }
};

module.exports = DiscountNavigation;
