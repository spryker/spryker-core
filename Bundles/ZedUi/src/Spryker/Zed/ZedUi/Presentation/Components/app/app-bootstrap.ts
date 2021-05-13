import {
    NgModule,
    APP_INITIALIZER,
    Compiler,
    Injector,
    InjectionToken,
    StaticProvider,
    ApplicationInitStatus,
    Inject,
    PlatformRef,
    ApplicationRef,
    DoBootstrap,
    Optional,
} from '@angular/core';
import { _getNgModules, ComponentsNgModule } from './registry';

export function appBootstrapProvider() {
    return {
        provide: APP_INITIALIZER,
        useFactory: appBootstrapFactory,
        deps: [Injector],
        multi: true,
    };
}

export function appBootstrapFactory(injector: Injector) {
    return () => {
        return new Promise((resolve, reject) => {
            const compiler = injector.get(Compiler);
            const appRef = injector.get(ApplicationRef);

            const initComponentsModule = async (ngModule: ComponentsNgModule) => {
                const moduleFactory = await compiler.compileModuleAsync(ngModule);
                const moduleRef = moduleFactory.create(injector);

                if (hasNgBootstrap(moduleRef.instance)) {
                    moduleRef.instance.ngDoBootstrap(appRef);
                }
            };

            _getNgModules({
                notifyOnModule: (ngModule) => initComponentsModule(ngModule),
            }).forEach((ngModule) => initComponentsModule(ngModule));
        });
    };
}

function hasNgBootstrap(ngModule: any): ngModule is DoBootstrap {
    return typeof ngModule.ngDoBootstrap === 'function';
}
