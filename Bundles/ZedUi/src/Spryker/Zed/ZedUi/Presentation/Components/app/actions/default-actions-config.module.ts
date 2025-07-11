import { NgModule } from '@angular/core';
import { AjaxFormComponent, AjaxFormModule } from '@spryker/ajax-form';
import { ActionsModule } from '@spryker/actions';
import { CloseDrawerActionHandlerModule, CloseDrawerActionHandlerService } from '@spryker/actions.close-drawer';
import { ConfirmationActionHandlerModule, ConfirmationActionHandlerService } from '@spryker/actions.confirmation';
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
import { OpenModalActionHandlerModule, OpenModalActionHandlerService } from '../open-modal';
import { CloseModalActionHandlerModule, CloseModalActionHandlerService } from '../close-modal';
import { RefreshModalActionHandlerModule, RefreshModalActionHandlerService } from '../refresh-modal';
import { SubmitFormActionHandlerService } from '../submit-form/submit-form-action-handler.service';
import { SubmitFormActionHandlerModule } from '../submit-form/submit-form-action-handler.module';
import { SubmitAjaxFormActionHandlerService } from '../submit-ajax-form/submit-ajax-form-action-handler.service';
import { SubmitAjaxFormActionHandlerModule } from '../submit-ajax-form/submit-ajax-form-action-handler.module';

declare module '@spryker/actions' {
    interface ActionsRegistry {
        'close-drawer': CloseDrawerActionHandlerService;
        confirmation: ConfirmationActionHandlerService;
        drawer: DrawerActionHandlerService;
        http: HttpActionHandlerService;
        notification: NotificationActionHandlerService;
        redirect: RedirectActionHandlerService;
        'refresh-drawer': RefreshDrawerActionHandlerService;
        'refresh-parent-table': RefreshParentTableActionHandlerService;
        'refresh-table': RefreshTableActionHandlerService;
        'open-modal': OpenModalActionHandlerService;
        'close-modal': CloseModalActionHandlerService;
        'refresh-modal': RefreshModalActionHandlerService;
        'submit-form': SubmitFormActionHandlerService;
        'submit-ajax-form': SubmitAjaxFormActionHandlerService;
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
            confirmation: ConfirmationActionHandlerService,
            drawer: DrawerActionHandlerService,
            http: HttpActionHandlerService,
            notification: NotificationActionHandlerService,
            redirect: RedirectActionHandlerService,
            'refresh-drawer': RefreshDrawerActionHandlerService,
            'refresh-parent-table': RefreshParentTableActionHandlerService,
            'refresh-table': RefreshTableActionHandlerService,
            'open-modal': OpenModalActionHandlerService,
            'close-modal': CloseModalActionHandlerService,
            'refresh-modal': RefreshModalActionHandlerService,
            'submit-form': SubmitFormActionHandlerService,
            'submit-ajax-form': SubmitAjaxFormActionHandlerService,
        }),
        DrawerActionModule.withComponents({
            'ajax-form': AjaxFormComponent,
            'url-html-renderer': UrlHtmlRendererComponent,
        }),
        CloseDrawerActionHandlerModule,
        ConfirmationActionHandlerModule,
        HttpActionHandlerModule,
        NotificationActionHandlerModule,
        RedirectActionHandlerModule,
        RefreshDrawerActionHandlerModule,
        RefreshParentTableActionHandlerModule,
        RefreshTableActionHandlerModule,
        AjaxFormModule,
        UrlHtmlRendererModule,
        OpenModalActionHandlerModule,
        CloseModalActionHandlerModule,
        RefreshModalActionHandlerModule,
        SubmitFormActionHandlerModule,
        SubmitAjaxFormActionHandlerModule,
    ],
})
export class DefaultActionsConfigModule {}
