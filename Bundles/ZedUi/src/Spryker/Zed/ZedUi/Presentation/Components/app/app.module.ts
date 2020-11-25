import { HttpClientModule } from '@angular/common/http';
import { Compiler, Injector, NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AjaxActionModule } from '@spryker/ajax-action';
import { AjaxPostActionCloseService } from '@spryker/ajax-action.post-action.close-overlay';
import { AjaxPostActionRedirectService } from '@spryker/ajax-action.post-action.redirect';
import { AjaxPostActionRefreshTableService } from '@spryker/ajax-action.post-action.refresh-table';
import { AjaxPostActionRefreshDrawerService } from '@spryker/ajax-action.post-action.refresh-drawer';
import { AjaxPostActionRefreshParentTableService } from '@spryker/ajax-action.post-action.refresh-parent-table';
import { LocaleModule } from '@spryker/locale';
import { DeLocaleModule } from '@spryker/locale/locales/de';
import { EN_LOCALE, EnLocaleModule } from '@spryker/locale/locales/en';
import { NotificationModule } from '@spryker/notification';
import { DefaultContextSerializationModule } from '@spryker/utils';
import { WebComponentsModule } from '@spryker/web-components';
import { ModalModule } from '@spryker/modal';
import { UnsavedChangesModule } from '@spryker/unsaved-changes';
import { UnsavedChangesBrowserGuard } from '@spryker/unsaved-changes.guard.browser';
import { UnsavedChangesDrawerGuardModule } from '@spryker/unsaved-changes.guard.drawer';
import { UnsavedChangesGuardNavigationModule } from '@spryker/unsaved-changes.guard.navigation';

import { _getNgModules, ComponentsNgModule } from './registry';
import { TableRootModule } from './table/table-root.module';

@NgModule({
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        LocaleModule.forRoot({ defaultLocale: EN_LOCALE }),
        EnLocaleModule,
        DeLocaleModule,
        TableRootModule,
        WebComponentsModule.forRoot(),
        NotificationModule.forRoot(),
        AjaxActionModule.withActions({
            close_overlay: AjaxPostActionCloseService,
            redirect: AjaxPostActionRedirectService,
            refresh_table: AjaxPostActionRefreshTableService,
            refresh_drawer: AjaxPostActionRefreshDrawerService,
            refresh_parent_table: AjaxPostActionRefreshParentTableService,
        }),
        DefaultContextSerializationModule,
        UnsavedChangesModule.forRoot(),
        UnsavedChangesDrawerGuardModule.forRoot(),
        UnsavedChangesGuardNavigationModule.forRoot(),
        UnsavedChangesModule.withGuard(UnsavedChangesBrowserGuard),
        ModalModule.forRoot(),
    ],
    providers: [],
})
export class AppModule {
    constructor(private injector: Injector, private compiler: Compiler) {}

    ngDoBootstrap() {
        _getNgModules({
            notifyOnModule: (ngModule) => this.initComponentsModule(ngModule),
        }).forEach((ngModule) => this.initComponentsModule(ngModule));
    }

    private async initComponentsModule(ngModule: ComponentsNgModule) {
        const moduleFactory = await this.compiler.compileModuleAsync(ngModule);

        const moduleRef = moduleFactory.create(this.injector);

        moduleRef.instance.ngDoBootstrap();
    }
}
