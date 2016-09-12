'use strict';

require('./main');

function Navigation(){
    this.currentTabPosition = 0;
    this.currentUrlHash = window.location.hash;
    this.tabUrls = $('#product-option-tabs').find('li a');

    this.checkActivatedTab();
    this.changeTabsOnClick();
    this.showHideNavigationButtons();
    this.listenNavigationButtons();
    this.markValidationFailedTabs();
}

Navigation.prototype.changeTabsOnClick = function(){
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

Navigation.prototype.markValidationFailedTabs = function() {
    $('.tab-content .tab-pane').each(function(index, tabContent) {
        var hasErrors = $(tabContent).find('.has-error, .alert-danger');
        if (hasErrors.length == 0) {
            return;
        }
        var tabContentElementId = $(tabContent).attr('id');
        $('#product-option-tabs, #option-value-translations').find('li a').each(function(index, tabElement) {
            var jTabElement = $(tabElement);
            var elementHref = jTabElement.attr('href');
            if (elementHref.indexOf(tabContentElementId) > -1) {
                jTabElement.addClass('error-tab');
                jTabElement.html(jTabElement.text() + " <i class='fa fa-warning'></i>");
            }
        })
    });
};

Navigation.prototype.listenNavigationButtons = function(){
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

Navigation.prototype.checkActivatedTab = function(){
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

Navigation.prototype.activateTab = function(element, hash){
    window.location.hash = hash;
    var productOptionForm = $('#product_option_general');
    var action = productOptionForm.attr('action');
    productOptionForm.attr('action', hash);
    element.tab('show');
};

Navigation.prototype.navigateElement = function(){
    var element = $('#product-option-tabs').find('li:eq(' + this.currentTabPosition + ') a');
    var hash = element.attr('href');

    this.proceedChange(element, hash);
};

Navigation.prototype.showHideNavigationButtons = function(){
    var self = this;

    if (self.currentTabPosition === 0) {
        $('#btn-tab-previous').addClass('hidden');
        $('#btn-tab-next').removeClass('hidden');
    } else if (self.currentTabPosition === (self.tabUrls.length - 1)) {
        $('#btn-tab-previous').removeClass('hidden');
        $('#btn-tab-next').addClass('hidden');
    } else {
        $('#btn-tab-previous').removeClass('hidden');
        $('#btn-tab-next').removeClass('hidden');
    }
};

Navigation.prototype.proceedChange = function(element, hash){
    this.activateTab(element, hash);
    this.checkActivatedTab();
};

Navigation.prototype.showHideNavigationButtons = function(){
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



module.exports = Navigation;
