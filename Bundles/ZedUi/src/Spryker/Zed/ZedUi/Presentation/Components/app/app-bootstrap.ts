import { ApplicationRef, APP_INITIALIZER, Compiler, DoBootstrap, Injector, StaticProvider } from '@angular/core';
import { ComponentsNgModule, _getNgModules } from './registry';

export function appBootstrapProvider(): StaticProvider {
    return {
        provide: APP_INITIALIZER,
        useFactory: appBootstrapFactory,
        deps: [Injector],
        multi: true,
    };
}

export function appBootstrapFactory(injector: Injector) {
    return () => {
        return new Promise(() => {
            // eslint-disable-next-line deprecation/deprecation
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

// eslint-disable-next-line @typescript-eslint/no-explicit-any
function hasNgBootstrap(ngModule: any): ngModule is DoBootstrap {
    return typeof ngModule.ngDoBootstrap === 'function';
}
