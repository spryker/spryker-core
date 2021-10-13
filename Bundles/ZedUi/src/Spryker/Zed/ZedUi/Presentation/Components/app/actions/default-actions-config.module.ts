import { NgModule } from '@angular/core';
import { AjaxFormComponent, AjaxFormModule } from '@spryker/ajax-form';
import { ActionsModule } from '@spryker/actions';
import { CloseDrawerActionHandlerModule, CloseDrawerActionHandlerService } from '@spryker/actions.close-drawer';
import { DrawerActionHandlerService, DrawerActionModule } from '@spryker/actions.drawer';
import { HttpActionHandlerModule, HttpActionHandlerService } from '@spryker/actions.http';
import { NotificationActionHandlerModule, NotificationActionHandlerService } from '@spryker/actions.notification';
import { RedirectActionHandlerModule, RedirectActionHandlerService } from '@spryker/actions.redirect';
import { RefreshDrawerActionHandlerModule, RefreshDrawerActionHandlerService } from '@spryker/actions.refresh-drawer';
import {
    RefreshParentTableActionHandlerModule,
    RefreshParentTableActionHandlerService,
} from '@spryker/actions.refresh-parent-table';
import { RefreshTableActionHandlerModule, RefreshTableActionHandlerService } from '@spryker/actions.refresh-table';
import { UrlHtmlRendererComponent } from '../url-html-renderer/url-html-renderer.component';
import { UrlHtmlRendererModule } from '../url-html-renderer/url-html-renderer.module';

declare module '@spryker/actions' {
    interface ActionsRegistry {
        'close-drawer': CloseDrawerActionHandlerService;
        drawer: DrawerActionHandlerService;
        http: HttpActionHandlerService;
        notification: NotificationActionHandlerService;
        redirect: RedirectActionHandlerService;
        'refresh-drawer': RefreshDrawerActionHandlerService;
        'refresh-parent-table': RefreshParentTableActionHandlerService;
        'refresh-table': RefreshTableActionHandlerService;
    }
}

declare module '@spryker/actions.drawer' {
    interface DrawerActionComponentsRegistry {
        'ajax-form': AjaxFormComponent;
        'url-html-renderer': UrlHtmlRendererComponent;
    }
}

@NgModule({
    imports: [
        ActionsModule.withActions({
            'close-drawer': CloseDrawerActionHandlerService,
            drawer: DrawerActionHandlerService,
            http: HttpActionHandlerService,
            notification: NotificationActionHandlerService,
            redirect: RedirectActionHandlerService,
            'refresh-drawer': RefreshDrawerActionHandlerService,
            'refresh-parent-table': RefreshParentTableActionHandlerService,
            'refresh-table': RefreshTableActionHandlerService,
        }),
        DrawerActionModule.withComponents({
            'ajax-form': AjaxFormComponent,
            'url-html-renderer': UrlHtmlRendererComponent,
        }),
        CloseDrawerActionHandlerModule,
        HttpActionHandlerModule,
        NotificationActionHandlerModule,
        RedirectActionHandlerModule,
        RefreshDrawerActionHandlerModule,
        RefreshParentTableActionHandlerModule,
        RefreshTableActionHandlerModule,
        AjaxFormModule,
        UrlHtmlRendererModule,
    ],
})
export class DefaultActionsConfigModule {}
