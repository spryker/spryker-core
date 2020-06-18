import { Type } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

export type ComponentsNgModule = Type<CustomElementModule>;

export type NgModuleReceiver = (ngModule: ComponentsNgModule) => void;

const knownNgModules: ComponentsNgModule[] = [];

const addToKnownModules: NgModuleReceiver = (ngModule) =>
    knownNgModules.push(ngModule);

const createCustomAddToKnownModules = (
    moduleReceiver: NgModuleReceiver
): NgModuleReceiver => (ngModule) => {
    addToKnownModules(ngModule);
    moduleReceiver(ngModule);
};

let ngModuleReceiver: NgModuleReceiver = addToKnownModules;

export function registerNgModule(ngModule: ComponentsNgModule): void {
    ngModuleReceiver(ngModule);
}

export function _getNgModules(options?: {
    notifyOnModule: NgModuleReceiver;
}): ComponentsNgModule[] {
    if (options?.notifyOnModule) {
        ngModuleReceiver = createCustomAddToKnownModules(options.notifyOnModule);
    }

    return knownNgModules;
}
