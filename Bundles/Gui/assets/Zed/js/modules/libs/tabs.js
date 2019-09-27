'use strict';

// TODO: add support for multi-tab navigation hash handling (e.g "/#tab1=tab-content-foo&tab2=tab-content-bar")

function Tabs(selector, onTabChange) {
    this.currentTabPosition = 0;
    this.currentUrlHash = window.location.hash;
    this.tabsContainer = $(selector);
    this.tabUrls = this.tabsContainer.find('.nav li a');
    this.onTabChange = onTabChange || function(){};

    this.checkErrors();
    this.setNavigation();
}

Tabs.prototype.checkErrors = function() {
    var self = this;

    if (self.tabsContainer.data('autoErrors') !== true) {
        return;
    }

    self.tabsContainer.find('.tab-content .tab-pane').each(function(i, tab) {
        var hasError = $(tab).find('.has-error, .alert-danger').length;
        var tabHeader = self.tabsContainer.find('.nav-tabs li[data-tab-content-id="' + tab.id + '"]');

        if (hasError) {
            tabHeader.addClass('error');
        } else {
            tabHeader.removeClass('error');
        }
    });
};

Tabs.prototype.setNavigation = function() {
    var self = this;

    if (self.tabsContainer.data('isNavigable') !== true) {
        return;
    }

    this.checkActivatedTab();
    this.changeTabsOnClick();
    this.showHideNavigationButtons();
    this.listenNavigationButtons();
};

Tabs.prototype.listenNavigationButtons = function() {
    var self = this;
    self.tabsContainer.find('.btn-tab-previous').on('click', function(event) {
        event.preventDefault();
        var element = $(this);
        var hash = element.attr('hash');

        self.currentTabPosition = Math.max(0, self.currentTabPosition - 1);
        self.currentUrlHash = hash;

        self.navigateElement();
        self.showHideNavigationButtons();
    });
    self.tabsContainer.find('.btn-tab-next').on('click', function(event) {
        event.preventDefault();
        var element = $(this);
        var hash = element.attr('href');

        self.currentTabPosition = Math.min(self.tabUrls.length, self.currentTabPosition + 1);
        self.currentUrlHash = hash;

        self.navigateElement();
        self.showHideNavigationButtons();
    });
};

Tabs.prototype.checkActivatedTab = function() {
    var self = this;
    var position = 0;
    var positionChanged = false;
    self.tabUrls.each(function() {
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

Tabs.prototype.changeTabsOnClick = function() {
    var self = this;
    self.tabUrls.on('click', function(event) {
        event.preventDefault();
        var selectedElement = $(this);
        var hash = selectedElement.attr('href');

        var position = 0;
        var positionChanged = false;

        self.tabUrls.each(function() {
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

Tabs.prototype.activateTab = function(element, hash) {
    window.location.hash = hash;
    element.tab('show');

    this.onTabChange(element.attr('href'));
};

Tabs.prototype.showHideNavigationButtons = function() {
    var self = this;

    if (self.currentTabPosition === 0) {
        self.tabsContainer.find('.btn-tab-previous').addClass('hidden');
        self.tabsContainer.find('.btn-tab-next').removeClass('hidden');
    } else  if (self.currentTabPosition === (self.tabUrls.length - 1)) {
        self.tabsContainer.find('.btn-tab-previous').removeClass('hidden');
        self.tabsContainer.find('.btn-tab-next').addClass('hidden');
    } else {
        self.tabsContainer.find('.btn-tab-previous').removeClass('hidden');
        self.tabsContainer.find('.btn-tab-next').removeClass('hidden');
    }
};

Tabs.prototype.navigateElement = function() {
    var self = this;

    var element = self.tabsContainer.find('li:eq(' + this.currentTabPosition + ') a');
    var hash = element.attr('href');

    this.proceedChange(element, hash);
};

Tabs.prototype.proceedChange = function(element, hash) {
    this.activateTab(element, hash);
    this.checkActivatedTab();
};

module.exports = Tabs;
